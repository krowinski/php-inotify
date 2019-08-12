<?php
declare(strict_types=1);

namespace Inotify;


use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class InotifyEvent implements Arrayable, JsonSerializable
{
    private $id;
    private $inotifyEventCodeEnum;
    private $uniqueId;
    private $fileName;
    private $watchedResource;
    private $timestamp;

    public function __construct(
        int $descriptor,
        InotifyEventCodeEnum $inotifyEventCodeEnum,
        int $uniqueId,
        string $fileName,
        WatchedResource $watchedResource,
        int $timestamp
    ) {
        $this->id = $descriptor;
        $this->inotifyEventCodeEnum = $inotifyEventCodeEnum;
        $this->uniqueId = $uniqueId;
        $this->fileName = $fileName;
        $this->watchedResource = $watchedResource;
        $this->timestamp = $timestamp;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getWatchedResource(): WatchedResource
    {
        return $this->watchedResource;
    }

    public function __toString(): string
    {
        return (string)print_r($this->toArray(), true);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'eventCode' => (string)$this->getInotifyEventCodeEnum(),
            'eventDescription' => $this->getInotifyEventCodeDescription(),
            'uniqueId' => $this->getUniqueId(),
            'fileName' => $this->getFileName(),
            'pathName' => $this->watchedResource->getPathname(),
            'customName' => $this->watchedResource->getCustomName(),
            'pathWithFile' => $this->getPathWithFile(),
            'timestamp' => $this->getTimestamp()
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInotifyEventCodeEnum(): InotifyEventCodeEnum
    {
        return $this->inotifyEventCodeEnum;
    }

    public function getInotifyEventCodeDescription(): string
    {
        return InotifyEventCodeEnum::getCodeDescription((int)$this->getInotifyEventCodeEnum()->getValue());
    }

    public function getUniqueId(): int
    {
        return $this->uniqueId;
    }

    public function getPathWithFile(): string
    {
        $path = $this->watchedResource->getPathname();
        if ('' === $this->getFileName()) {
            return $path;
        }

        return $path . DIRECTORY_SEPARATOR . $this->getFileName();
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}