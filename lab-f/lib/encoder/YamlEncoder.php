<?php

namespace App\Encoder;

class YamlEncoder implements EncoderInterface
{
    public function supports(string $format): bool
    {
        return 'YAML' === $format;
    }

    public function getName(): string
    {
        return 'YAML';
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function decode(string $data): array
    {
        $result = [];
        $lines = array_filter(explode("\n", $data), function($line) {
            $trimmed = trim($line);
            return '' !== $trimmed && 0 !== strpos($trimmed, '#');
        });

        $currentItem = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            $indent = strlen($line) - strlen(ltrim($line));

            if ($trimmed === '-' || 0 === strpos($trimmed, '- ')) {
                if (!empty($currentItem)) {
                    $result[] = $currentItem;
                }
                $currentItem = [];

                if (0 === strpos($trimmed, '- ') && false !== strpos($trimmed, ':')) {
                    $rest = substr($trimmed, 2);
                    $parts = explode(':', $rest, 2);
                    $key = trim($parts[0]);
                    $value = isset($parts[1]) ? trim($parts[1]) : '';
                    $currentItem[$key] = $value;
                }
            }
            elseif (false !== strpos($trimmed, ':') && $indent > 0) {
                $parts = explode(':', $trimmed, 2);
                $key = trim($parts[0]);
                $value = isset($parts[1]) ? trim($parts[1]) : '';
                $currentItem[$key] = $value;
            }
        }

        if (!empty($currentItem)) {
            $result[] = $currentItem;
        }

        return $result;
    }

    /**
     * @param array<int, array<string, string>> $data
     */
    public function encode(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $lines = [];
        foreach ($data as $item) {
            $lines[] = '-';
            foreach ($item as $key => $value) {
                $lines[] = '  ' . $key . ': ' . $value;
            }
        }

        return implode("\n", $lines);
    }
}
