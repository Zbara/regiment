<?php

namespace App\Service;

class Encrypt
{

    var array $letters = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

    public function encode($data)
    {
        $data = str_split($data);
        $search = "";
        $obj = [
            "a" => "b",
            "b" => "h",
            "c" => "k",
            "d" => "d",
            "e" => "u",
            "f" => "g",
            "g" => "t",
            "h" => "i",
            "i" => "l",
            "j" => "a",
            "k" => "e",
            "l" => "w",
            "m" => "r",
            "n" => "p",
            "o" => "x",
            "p" => "y",
            "q" => "m",
            "r" => "z",
            "s" => "c",
            "t" => "v",
            "u" => "j",
            "v" => "s",
            "w" => "f",
            "x" => "o",
            "y" => "q",
            "z" => "n",
            "A" => "E",
            "B" => "G",
            "C" => "J",
            "D" => "P",
            "E" => "L",
            "F" => "U",
            "G" => "W",
            "H" => "S",
            "I" => "V",
            "J" => "A",
            "K" => "N",
            "L" => "R",
            "M" => "C",
            "N" => "Z",
            "O" => "X",
            "P" => "M",
            "Q" => "Y",
            "R" => "B",
            "S" => "I",
            "T" => "H",
            "U" => "F",
            "V" => "K",
            "W" => "Q",
            "X" => "O",
            "Y" => "D",
            "Z" => "T",
            0 => "2",
            1 => "6",
            2 => "0",
            3 => "7",
            4 => "5",
            5 => "1",
            6 => "4",
            7 => "8",
            8 => "3",
            9 => "9"
        ];
        foreach ($data as $key => $item) {
            if (in_array($item, $this->letters)) {
                $search = $search . $obj[$item];
            } else {
                $search = $search . $item;
            }
        }
        return $search;
    }

    public function decode($data): string
    {
        $data = str_split($data);

        $search = "";
        $obj = [
            "b" => "a",
            "h" => "b",
            "k" => "c",
            "d" => "d",
            "u" => "e",
            "g" => "f",
            "t" => "g",
            "i" => "h",
            "l" => "i",
            "a" => "j",
            "e" => "k",
            "w" => "l",
            "r" => "m",
            "p" => "n",
            "x" => "o",
            "y" => "p",
            "m" => "q",
            "z" => "r",
            "c" => "s",
            "v" => "t",
            "j" => "u",
            "s" => "v",
            "f" => "w",
            "o" => "x",
            "q" => "y",
            "n" => "z",
            "E" => "A",
            "G" => "B",
            "J" => "C",
            "P" => "D",
            "L" => "E",
            "U" => "F",
            "W" => "G",
            "S" => "H",
            "V" => "I",
            "A" => "J",
            "N" => "K",
            "R" => "L",
            "C" => "M",
            "Z" => "N",
            "X" => "O",
            "M" => "P",
            "Y" => "Q",
            "B" => "R",
            "I" => "S",
            "H" => "T",
            "F" => "U",
            "K" => "V",
            "Q" => "W",
            "O" => "X",
            "D" => "Y",
            "T" => "Z",
            2 => "0",
            6 => "1",
            0 => "2",
            7 => "3",
            5 => "4",
            1 => "5",
            4 => "6",
            8 => "7",
            3 => "8",
            9 => "9"
        ];

        foreach ($data as $key => $item) {
            if (in_array($item, $this->letters)) {
                $search = $search . $obj[$item];
            } else {
                $search = $search . $item;
            }
        }
        return $search;
    }
}
