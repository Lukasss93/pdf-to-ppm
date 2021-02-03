<?php

namespace Lukasss93\PdfToPpm;

use DateTime;

class PdfInfoData
{
    /** @var string[] */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getTitle(): string
    {
        return $this->data['Title'];
    }

    public function getCreator(): string
    {
        return $this->data['Creator'];
    }

    public function getProducer(): string
    {
        return $this->data['Producer'];
    }

    public function getCreationDate(): DateTime
    {
        return DateTime::createFromFormat('D M  j H:i:s Y e', $this->data['CreationDate']);
    }

    public function getModDate(): DateTime
    {
        return DateTime::createFromFormat('D M  j H:i:s Y e', $this->data['ModDate']);
    }

    public function getTagged(): string
    {
        return $this->data['Tagged'];
    }

    public function getUserProperties(): string
    {
        return $this->data['UserProperties'];
    }

    public function getSuspects(): string
    {
        return $this->data['Suspects'];
    }

    public function getForm(): string
    {
        return $this->data['Form'];
    }

    public function getJavascript(): string
    {
        return $this->data['Javascript'];
    }

    public function getPages(): int
    {
        return (int) $this->data['Pages'];
    }

    public function getEncrypted(): string
    {
        return $this->data['Encrypted'];
    }

    public function getPageSize(): string
    {
        return $this->data['Page size'];
    }

    public function getPageRot(): string
    {
        return $this->data['Page rot'];
    }

    public function getFileSize(): string
    {
        return $this->data['File size'];
    }

    public function getOptimized(): string
    {
        return $this->data['Optimized'];
    }

    public function getPdfVersion(): string
    {
        return $this->data['PDF version'];
    }

    public function toArray(): array
    {
        return $this->data;
    }
}