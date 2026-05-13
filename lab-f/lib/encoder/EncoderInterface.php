<?php

namespace App\Encoder;

interface EncoderInterface
{
    //Sprawdza czy enkoder obsługuje dany format
    public function supports(string $format): bool;

    //dekoduje tekst do tablicy asocjacyjnej | @return array<int, array<string, string>>
    public function decode(string $data): array;

    //koduje tablicę asocjacyjną na tekst | @param array<int, array<string, string>> $data
    public function encode(array $data): string;

    //zwraca format/name
    public function getName(): string;
}

