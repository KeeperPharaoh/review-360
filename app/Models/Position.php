<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name'
    ];
    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function getName(): ?string
    {
        return $this->getAttribute('name');
    }

    public function getCreatedAt()
    {
        return $this->getAttribute('created_at');
    }

    public function getUpdatedAt()
    {
        return $this->getAttribute('updated_at');
    }

    public function setName(string $name): void
    {
        $this->setAttribute('name', $name);
    }
}
