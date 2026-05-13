<?php

namespace App\Encoder;

class SsvEncoder implements EncoderInterface
{
    private const SEPARATOR = ';';

    public function supports(string $format): bool
    {
        return 'SSV' === $format;
    }

    public function getName(): string
    {
        return 'SSV';
    }

    //@return array<int, array<string, string>>
    public function decode(string $data): array
    {
        $lines = array_filter(explode("\n", trim($data)), fn($line) => '' !== trim($line));
        if (count($lines) < 1) {
            return [];
        }

        $header = str_getcsv(array_shift($lines), self::SEPARATOR, '"', '');
        $result = [];

        foreach ($lines as $line) {
            $values = str_getcsv($line, self::SEPARATOR, '"', '');
            $values = array_pad($values, count($header), '');
            $values = array_slice($values, 0, count($header));
            $result[] = array_combine($header, $values);
        }

        return $result;
    }

    //@param array<int, array<string, string>> $data
    public function encode(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $header = array_keys($data[0]);
        $lines = [implode(self::SEPARATOR, $header)];

        foreach ($data as $row) {
            $values = [];
            foreach ($header as $key) {
                $values[] = $row[$key] ?? '';
            }
            $lines[] = implode(self::SEPARATOR, $values);
        }

        return implode("\n", $lines);
    }
}

