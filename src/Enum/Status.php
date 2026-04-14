<?php

namespace App\Enum;

enum Status: string 
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Archived = 'archived';

    public function getLabel(): string
    {
        return match($this) {
            self::Pending => 'À faire',
            self::Archived => 'Archivé',
            self::Completed => 'Terminé',
        };
    }
}

?>