<?php
/**
 * classe raccourcis pour remplacer la fonction muette __( )
 * traduction de texte
 */
class Trad {

    /** @var string  */
    private $key;

    /** @var string  */
    private $file;

    /** @var bool */
    private $backslash;

    /** @var string */
    private $prefix;

    /** @var string */
    private $suffix;

    public function __construct(string $key, string $file = __FILE__, bool $backslash = false, string $prefix = '', string $suffix = '') {
        $this->key = $key;
        $this->file = $file;
        $this->backslash = $backslash;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public function __toString(): string {
        return $this->prefix.\translate::sentence(str_replace("\'", "'", $this->key), $this->file, $this->backslash).$this->suffix;
    }

}