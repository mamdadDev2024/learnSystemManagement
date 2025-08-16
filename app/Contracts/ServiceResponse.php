<?php

namespace App\Contracts;

class ServiceResponse
{
    public function __construct(
        public bool $status,
        public ?string $message = null,
        public mixed $data = null,
    ) {}

    public static function success(mixed $data = null, string $message = 'Success'): self
    {
        return new self(true, $message, $data);
    }

    public static function error(string $message = 'Error', mixed $data = null): self
    {
        return new self(false, $message, $data);
    }

    public static function validation(string $message, mixed $errors = null): self
    {
        return new self(false, $message, $errors);
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status ? 'success' : 'error',
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
