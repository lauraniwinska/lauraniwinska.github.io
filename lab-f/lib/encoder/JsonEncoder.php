<?php

namespace App\Encoder;

class JsonEncoder implements EncoderInterface
{
    public function supports(string $format): bool
    {
        return 'JSON' === $format;
    }

    public function getName(): string
    {
        return 'JSON';
    }

    // @return array<int, array<string, string>>
    public function decode(string $data): array
    {
        $decoded = json_decode($data, true);

        if (!is_array($decoded)) {
            return [];
        }
        return array_values(array_filter(array_map(fn($item) => is_array($item) ? $item : [], $decoded), function($item) {
            return is_array($item) && !empty($item);
        }));
    }

    //@param array<int, array<string, string>> $data
    public function encode(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
