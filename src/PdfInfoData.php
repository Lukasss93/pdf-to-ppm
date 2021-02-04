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

    public function getTitle(): ?string
    {
        return $this->data['Title'] ?? null;
    }

    public function getCreator(): ?string
    {
        return $this->data['Creator'] ?? null;
    }

    public function getProducer(): ?string
    {
        return $this->data['Producer'] ?? null;
    }

    public function getCreationDate(): ?DateTime
    {
        $value = $this->data['CreationDate'] ?? null;
        return $value === null ? null : DateTime::createFromFormat('D M  j H:i:s Y e', $value);
    }

    public function getModDate(): ?DateTime
    {
        $value = $this->data['ModDate'] ?? null;
        return $value === null ? null : DateTime::createFromFormat('D M  j H:i:s Y e', $value);
    }

    public function getTagged(): ?string
    {
        return $this->data['Tagged'] ?? null;
    }

    public function getUserProperties(): ?string
    {
        return $this->data['UserProperties'] ?? null;
    }

    public function getSuspects(): ?string
    {
        return $this->data['Suspects'] ?? null;
    }

    public function getForm(): ?string
    {
        return $this->data['Form'] ?? null;
    }

    public function getJavascript(): ?string
    {
        return $this->data['Javascript'] ?? null;
    }

    public function getPages(): ?int
    {
        $value = $this->data['Pages'] ?? null;
        return $value === null ? null : (int) $value;
    }

    public function getEncrypted(): ?string
    {
        return $this->data['Encrypted'] ?? null;
    }

    public function getPageSize(): ?string
    {
        return $this->data['Page size'] ?? null;
    }

    public function getPageRot(): ?string
    {
        return $this->data['Page rot'] ?? null;
    }

    public function getFileSize(): ?string
    {
        return $this->data['File size'] ?? null;
    }

    public function getOptimized(): ?string
    {
        return $this->data['Optimized'] ?? null;
    }

    public function getPdfVersion(): ?string
    {
        return $this->data['PDF version'] ?? null;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}