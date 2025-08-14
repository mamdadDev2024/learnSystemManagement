<?php

namespace App;

class ServiceResponse
{
    public function __construct(
        public string $status,
        public ?string $message = null,
        public mixed $data = null
    ) {}

    public static function success(mixed $data = null, string $message = 'Success'): self
    {
        return new self('success', $message, $data);
    }

    public static function error(string $message = 'Error', mixed $data = null): self
    {
        return new self('error', $message, $data);
    }

    public static function validation(string $message, mixed $errors = null): self
    {
        return new self('validation_error', $message, $errors);
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
