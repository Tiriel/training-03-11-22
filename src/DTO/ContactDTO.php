<?php

namespace App\DTO;

class ContactDTO
{
    private ?string $name = null;

    private ?string $email = null;

    private ?string $subject = null;

    private ?string $message = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ContactDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): ContactDTO
    {
        $this->email = $email;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): ContactDTO
    {
        $this->subject = $subject;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): ContactDTO
    {
        $this->message = $message;
        return $this;
    }
}