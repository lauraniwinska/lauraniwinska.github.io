<?php

namespace App;

use App\Encoder\EncoderInterface;

class Serializer
{
    /** @var EncoderInterface[] */
    private array $encoders = [];

    public function __construct()
    {
        $this->registerEncoder(new Encoder\CsvEncoder());
        $this->registerEncoder(new Encoder\SsvEncoder());
        $this->registerEncoder(new Encoder\TsvEncoder());
        $this->registerEncoder(new Encoder\JsonEncoder());
        $this->registerEncoder(new Encoder\YamlEncoder());
    }

    public function registerEncoder(EncoderInterface $encoder): void
    {
        $this->encoders[] = $encoder;
    }

    //znajduje enkoder dla danego formatu
    private function getEncoder(string $format): ?EncoderInterface
    {
        foreach ($this->encoders as $encoder) {
            if ($encoder->supports($format)) {
                return $encoder;
            }
        }
        return null;
    }

    //konwertuje dane z jednego formatu na drugi
    public function convert(string $inputData, string $inputFormat, string $outputFormat): ?string
    {
        $inputEncoder = $this->getEncoder($inputFormat);
        $outputEncoder = $this->getEncoder($outputFormat);

        if (!$inputEncoder || !$outputEncoder) {
            return null;
        }

        try {
            // dekoduje dane wejściowe do tablicy
            $data = $inputEncoder->decode($inputData);

            // zakoduje dane wyjściowe w wymaganym formacie
            return $outputEncoder->encode($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    //zwraca listę dostępnych formatów | @return string[]
    public function getFormats(): array
    {
        return array_map(fn(EncoderInterface $encoder) => $encoder->getName(), $this->encoders);
    }
}

