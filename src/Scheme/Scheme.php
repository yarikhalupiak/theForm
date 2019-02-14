<?php

declare(strict_types = 1);

namespace App\Scheme;

class Scheme
{
    /**
     * @var SchemeId
     */
    protected $schemeId;
    /**
     * @var UserId
     */
    protected $userId;
    /**
     * @var string
     */
    protected $content;
    /**
     * @var string
     */
    protected $address;
    /**
     * @var \DateTime
     */
    protected $createdOn;
    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * @param SchemeId $schemeId
     * @param UserId $userId
     * @param string $address
     * @param string $content
     */
    public function __construct(SchemeId $schemeId, UserId $userId, $address, $content)
    {
        $this->schemeId = $schemeId;
        $this->userId = $userId;
        $this->setContent($content);
        $this->setAddress($address);
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    /**
     * @param $content
     */
    protected function setContent($content)
    {
        $content = trim($content);

        if (!$content) {
            throw new \InvalidArgumentException('Message cannot be empty');
        }

        $this->content = $content;
    }

    private function setAddress($address)
    {
        $address = trim($address);

        if (!$address) {
            throw new \InvalidArgumentException('Address cannot be empty');
        }

        $this->address = $address;
    }

    /**
     * @return SchemeId
     */
    public function id()
    {
        return $this->schemeId;
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function address()
    {
        return $this->address;
    }

    public function changeContent($content)
    {
        $this->setContent($content);
        return $this;
    }

    public function changeAddress($address)
    {
        $this->setAddress($address);

        return $this;
    }

    public function content()
    {
        return $this->content;
    }
}