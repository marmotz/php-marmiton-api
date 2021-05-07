<?php

namespace MarmitonApi\Tools;

class QueryBuilder
{
    private UrlSearchParams $queryParams;

    public function __construct()
    {
        $this->queryParams = new UrlSearchParams();
    }

    public function build(): string
    {
        // The title query in mandatory, but can be empty
        if (!$this->queryParams->has('aqt')) {
            $this->queryParams->set('aqt', '');
        }

        return (string) $this->queryParams;
    }

    /**
     * Without any cooking whatsoever
     */
    public function raw(): self
    {
        $this->queryParams->append('rct', '3');

        return $this;
    }

    public function takingLessThan(int $minutes): self
    {
        $this->queryParams->append('ttlt', $minutes);

        return $this;
    }

    public function vegan(): self
    {
        $this->queryParams->append('prt', '3');

        return $this;
    }

    public function vegetarian(): self
    {
        $this->queryParams->append('prt', '1');

        return $this;
    }

    public function withDifficulty(int $difficulty): self
    {
        $this->queryParams->append('dif', $difficulty);

        return $this;
    }

    /**
     * There must be photo of the final product.
     */
    public function withPhoto(): self
    {
        $this->queryParams->append('pht', '1');

        return $this;
    }

    public function withPrice(int $price): self
    {
        $this->queryParams->append('exp', $price);

        return $this;
    }

    public function withTitleContaining(string $text): self
    {
        $this->queryParams->append('aqt', $text);

        return $this;
    }

    public function withType(int $type): self
    {
        $this->queryParams->append('dt', $type);

        return $this;
    }

    public function withoutDairyProducts(): self
    {
        $this->queryParams->append('prt', '4');

        return $this;
    }

    public function withoutGluten(): self
    {
        $this->queryParams->append('prt', '2');

        return $this;
    }

    public function withoutOven(): self
    {
        $this->queryParams->delete('rct');
        $this->queryParams->append('rct', '2');
        $this->queryParams->append('rct', '3');

        return $this;
    }
}
