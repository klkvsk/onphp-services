<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-12-03
 */
namespace OnPhp\Services\Codegen;

use \WrongArgumentException;
use \WrongStateException;

abstract class AbstractSourceCodeBuilder
{
    protected $eol = PHP_EOL;
    protected $line = 0;
    protected $lines = [];
    protected $indentLevel = 0;
    protected $indentation = '    '; // 4 spaces

    abstract public function build();

    public function getOutput()
    {
        return implode('', $this->lines);
    }

    public function saveTo($filepath, $overwrite = true)
    {
        if (!$overwrite && file_exists($filepath)) {
            return false;
        } else if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0777, true);
        }

        if ($this->hasSourceCodeDiff($filepath)) {
            file_put_contents($filepath, $this->getOutput());
            chmod($filepath, 0666);
            return true;
        } else {
            return false;
        }
    }

    public function hasSourceCodeDiff($existingFile)
    {
        if (!file_exists($existingFile)) {
            return true;
        }
        $existing = file($existingFile);
        $lineCount = count($this->lines) - 1;
        if (count($existing) != $lineCount) {
            return true;
        }

        for ($i = 0; $i < $lineCount; $i++) {
            $a = $this->lines[$i];
            $b = $existing[$i];

            if (strpos($a, '@nodiff') !== false && strpos($b, '@nodiff') !== false) {
                continue;
            }

            if (strcmp($a, $b) != 0) {
                return true;
            }
        }
        return false;
    }

    public function setLineEndings($eol)
    {
        $this->eol = $eol;
        return $this;
    }

    public function getLineEndings()
    {
        return $this->eol;
    }

    public function setIndentation($indentation)
    {
        $this->indentation = $indentation;
        return $this;
    }

    public function getIndentation()
    {
        return $this->indentation;
    }

    public function addLine($line)
    {
        $this->add($line)->newLine();
        return $this;
    }

    public function newLine()
    {
        $this->add($this->eol);
        $this->line++;
        $this->add(str_repeat($this->indentation, $this->indentLevel));
        return $this;
    }

    public function add($line)
    {
        if (!isset($this->lines[$this->line])) {
            $this->lines[$this->line] = '';
        }
        $this->lines[$this->line] .= $line;
        return $this;
    }

    public function begin($opening = ' {')
    {
        return $this->add($opening)->newLine()->indent();
    }

    public function end($ending = '}')
    {
        $this->unindent();
        if (!preg_match('/^\s+$/', $this->lines[$this->line])) {
            $this->newLine();
        }
        $this->addLine($ending);
        return $this;
    }

    public function indent()
    {
        $this->indentLevel++;
        $this->add($this->indentation);
        return $this;
    }

    public function unindent()
    {
        if ($this->indentLevel == 0) {
            throw new WrongStateException('trying to unintend non-intended line');
        }
        $this->indentLevel--;
        $lineLen = strlen($this->lines[$this->line]);
        $indentLen = strlen($this->indentation);
        if ($lineLen >= $indentLen && substr($this->lines[$this->line], -$indentLen) === $this->indentation
        ) {
            $this->lines[$this->line] = substr($this->lines[$this->line], 0, $lineLen - $indentLen);
        }
        return $this;
    }

    public function clean()
    {
        $this->lines = [];
        $this->line = 0;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    abstract public function addValue($value);

    /**
     * @param array $args
     * @return $this
     * @throws WrongArgumentException
     */
    public function addArguments(array $args)
    {
        $this->add('(');
        $num = count($args);
        foreach ($args as $arg) {
            $this->addValue($arg);
            if (--$num > 0) {
                $this->add(', ');
            }
        }
        $this->add(')');
        return $this;
    }

    public function doc($doc)
    {
        $columnWidths = [];

        if (is_array($doc)) {
            $this->addLine('/**');
            foreach ($doc as $docLine) {
                $this->add(' * ');
                if (is_array($docLine)) {
                    $columns = count($docLine);
                    $lastColumn = array_pop($docLine);
                    foreach ($docLine as $i => $docLineCol) {
                        if (!isset($columnWidths[$i])) {
                            $columnWidths[$i] = max(array_map(
                                function ($d) use ($i) {
                                    return strlen($d[$i]);
                                },
                                array_filter($doc, function ($d) use ($columns) {
                                    return count($d) == $columns;
                                })
                            ));
                        }
                        $this->add(sprintf('%-' . $columnWidths[$i] . 's', $docLineCol) . ' ');
                    }
                    $this->addLine($lastColumn);
                } else if (is_string($docLine)) {
                    $this->addLine($docLine);
                }
            }
            $this->addLine(' */');
        } else if (is_string($doc) || $doc) {
            $this->addLine('/** ' . $doc . ' */');
        } else {
            throw new WrongArgumentException;
        }
        return $this;
    }

    public function addHeaderDocForAutogenerated()
    {
        return $this->doc([
            'This file is auto-generated',
            '/!\ DO NOT MODIFY /!\\',
            '',
            ['@nodiff', 'last modified', date('Y-m-d H:i:s')]
        ]);
    }

    public function addHeaderDocForGeneratedOnce()
    {
        return $this->doc([
            'This file is autogenerated only if it does not exist',
            'Feel free to modify it, your code would not be overwritten',
            '',
            ['@nodiff', 'created', date('Y-m-d H:i:s')]
        ]);
    }

}