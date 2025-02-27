<?php declare(strict_types=1);

namespace AP\ToObject\Tests\Objects;

enum Genre: string
{
    case FICTION         = 'Fiction';
    case NON_FICTION     = 'Non-Fiction';
    case SCIENCE_FICTION = 'Science Fiction';
    case FANTASY         = 'Fantasy';
    case MYSTERY         = 'Mystery';
    case BIOGRAPHY       = 'Biography';
    case HISTORY         = 'History';
}