<?php

class Usuario
{
    public int $id = 0;
    public string $nombre = "";
    public string $apellido = "";
    public DateTime $nacimiento;
    public int $edad = 0;
    public string $documento = "";
    public string $nacionalidad = "";
    public string $genero = "";
    public string $correo = "";
    public string $direccion = "";
    public string $pais = "";
    public string $ciudad = "";
    public string $localidad = "";
    public string $telefono = "";
    public string $etiqueta = "";
    public string $foto = "";
    public DateTime $fecha_creacion;
    public bool $activo = false;
    
    
    public function __construct(array $u = null)
    {
        if($u != null && isset($u['id']))
        {
            $this->id = (int)$u['id'];
            $this->nombre = "".$u['nombre'];
            $this->apellido = "".$u['apellido'];
            $this->nacimiento = new DateTime($u['nacimiento']);
            $this->edad = (int)$u['edad'];
            $this->documento = "".$u['documento'];
            $this->nacionalidad = "".$u['nacionalidad'];
            $this->genero = "".$u['genero'];
            $this->correo = "".$u['correo'];
            $this->direccion = "".$u['direccion'];
            $this->pais = "".$u['pais'];
            $this->ciudad = "".$u['ciudad'];
            $this->localidad = "".$u['localidad'];
            $this->telefono = "".$u['telefono'];
            $this->etiqueta = "".$u['etiqueta'];
            $this->foto = "".$u['foto'];
            $this->fecha_creacion = new DateTime($u['fecha_creacion']);
            $this->activo = (bool)$u['activo'];
        }
        else
        {
            $this->fecha_creacion = new DateTime("now");
        }
        
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'edad' => $this->edad,
            'documento' => $this->documento,
            'nacionalidad' => $this->nacionalidad,
            'genero' => $this->genero,
            'correo' => $this->correo,
            'direccion' => $this->direccion,
            'pais' => $this->pais,
            'ciudad' => $this->ciudad,
            'localidad' => $this->localidad,
            'telefono' => $this->telefono,
            'etiqueta' => $this->etiqueta,
            'foto' => $this->foto,
            'activo' => $this->activo,
            'fecha_creacion' => $this->fecha_creacion->format('Y-m-d H:i:s'),
        );
    }

}

