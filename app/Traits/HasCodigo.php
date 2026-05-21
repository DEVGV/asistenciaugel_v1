<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait para auto-generar códigos alfanuméricos únicos en modelos.
 *
 * Uso: Agregar `use HasCodigo;` en el modelo y definir las propiedades opcionales:
 *
 *   - `protected string $codigoPrefix = 'TRA';`  → Prefijo del código (por defecto: primeras 3 letras de la tabla)
 *   - `protected string $codigoColumn = 'codigo';` → Columna donde se guarda (por defecto: 'codigo')
 *   - `protected int $codigoPadLength = 5;` → Largo del número secuencial con padding (por defecto: 5)
 *
 * Ejemplo de códigos generados: TRA00001, TRA00002, ARE00001, CAR00001
 */
trait HasCodigo
{
    public static function bootHasCodigo(): void
    {
        static::creating(function (Model $model) {
            $column = $model->getCodigoColumn();

            if (empty($model->{$column})) {
                $model->{$column} = $model->generateCodigo();
            }
        });
    }

    /**
     * Genera el siguiente código secuencial para el modelo.
     */
    public function generateCodigo(): string
    {
        $prefix = $this->getCodigoPrefix();
        $column = $this->getCodigoColumn();
        $padLength = $this->getCodigoPadLength();

        $lastCodigo = static::query()
            ->where($column, 'like', "{$prefix}%")
            ->orderByRaw("CAST(SUBSTRING({$column} FROM '\\d+$') AS INTEGER) DESC")
            ->value($column);

        if ($lastCodigo) {
            $lastNumber = (int) preg_replace('/\D/', '', substr($lastCodigo, strlen($prefix)));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix.str_pad((string) $nextNumber, $padLength, '0', STR_PAD_LEFT);
    }

    /**
     * Obtiene el prefijo del código.
     * Puede ser personalizado en cada modelo con `protected string $codigoPrefix`.
     */
    public function getCodigoPrefix(): string
    {
        if (property_exists($this, 'codigoPrefix')) {
            return $this->codigoPrefix;
        }

        return $this->resolveDefaultPrefix();
    }

    /**
     * Obtiene el nombre de la columna del código.
     */
    public function getCodigoColumn(): string
    {
        if (property_exists($this, 'codigoColumn')) {
            return $this->codigoColumn;
        }

        return 'codigo';
    }

    /**
     * Obtiene el largo del padding numérico.
     */
    public function getCodigoPadLength(): int
    {
        if (property_exists($this, 'codigoPadLength')) {
            return $this->codigoPadLength;
        }

        return 5;
    }

    /**
     * Resuelve un prefijo por defecto basado en el nombre de la tabla.
     * Ejemplo: t_trabajador → TRA, t_areas → ARE, t_cargos → CAR
     */
    private function resolveDefaultPrefix(): string
    {
        $table = $this->getTable();

        $cleanName = preg_replace('/^(t_|conasis\.t_)/', '', $table);

        return mb_strtoupper(mb_substr($cleanName, 0, 3));
    }
}
