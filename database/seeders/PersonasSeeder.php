<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonasSeeder extends Seeder
{
    /**
     * Seed 15 personas reales con domicilio, email y teléfono relacionados.
     *
     * Asume:
     *   param.t00_sexos           → id 1 = Masculino, id 2 = Femenino
     *   param.t3_tipoDocIdentidad → id 1 = DNI
     *   param.t00_operadores      → id 1 = Movistar, id 2 = Claro, id 3 = Entel, id 4 = Bitel
     *   t_zonas                   → id 1..N (usa los primeros disponibles ciclicamente)
     */
    public function run(): void
    {
        // ── 1. Resolver IDs de parámetros ─────────────────────────────────────
        $sexoM = DB::table('param.t00_sexos')->where('codigo', 'M')->orWhere('nombre', 'ILIKE', '%masculino%')->value('id') ?? 1;
        $sexoF = DB::table('param.t00_sexos')->where('codigo', 'F')->orWhere('nombre', 'ILIKE', '%femenino%')->value('id') ?? 2;
        $dniId = DB::table('param.t3_tipoDocIdentidad')->where('abreviatura', 'ILIKE', '%DNI%')->value('id') ?? 1;
        $paisPE = DB::table('param.t26_pais')->where('codigo', 'PE')->orWhere('nombre', 'ILIKE', '%perú%')->value('id') ?? 1;

        // Operadores (ciclo si no existen todos)
        $opMovistar = DB::table('param.t00_operadores')->where('nombre', 'ILIKE', '%movistar%')->value('id') ?? 1;
        $opClaro = DB::table('param.t00_operadores')->where('nombre', 'ILIKE', '%claro%')->value('id') ?? 2;
        $opEntel = DB::table('param.t00_operadores')->where('nombre', 'ILIKE', '%entel%')->value('id') ?? 3;
        $opBitel = DB::table('param.t00_operadores')->where('nombre', 'ILIKE', '%bitel%')->value('id') ?? 4;
        $operadores = array_filter([$opMovistar, $opClaro, $opEntel, $opBitel]);
        if (empty($operadores)) {
            $operadores = DB::table('param.t00_operadores')->pluck('id')->toArray();
        }
        if (empty($operadores)) {
            $operadores = [1]; // fallback
        }

        // Zonas (tomar las primeras 5 disponibles)
        $zonas = DB::table('t_zonas')->pluck('id')->toArray();
        if (empty($zonas)) {
            $zonas = [1]; // fallback: si no hay zonas aún
        }

        // ── 2. Datos de las 15 personas ───────────────────────────────────────
        $personas = [
            [
                'paterno' => 'QUISPE',
                'materno' => 'MAMANI',
                'nombre' => 'CARLOS ALBERTO',
                'sexo_id' => $sexoM,
                'docIdentidad' => '10234567',
                'fechaNac' => '1985-03-12',
                'ubigeo' => '150101',
                'email' => 'carlos.quispe@gmail.com',
                'telefono' => '987654321',
                'operador' => $opClaro,
                'domicilio' => 'AV. TUPAC AMARU 345, COMAS',
                'domUbigeo' => '150113',
            ],
            [
                'paterno' => 'FLORES',
                'materno' => 'CCARI',
                'nombre' => 'MARIA ELENA',
                'sexo_id' => $sexoF,
                'docIdentidad' => '20345678',
                'fechaNac' => '1990-07-25',
                'ubigeo' => '150101',
                'email' => 'maria.flores@hotmail.com',
                'telefono' => '976543210',
                'operador' => $opMovistar,
                'domicilio' => 'JR. LOS ROSALES 120, SAN BORJA',
                'domUbigeo' => '150140',
            ],
            [
                'paterno' => 'RODRIGUEZ',
                'materno' => 'HUANCA',
                'nombre' => 'JUAN PABLO',
                'sexo_id' => $sexoM,
                'docIdentidad' => '30456789',
                'fechaNac' => '1978-11-04',
                'ubigeo' => '210101',
                'email' => 'jrodriguez.ugel@gmail.com',
                'telefono' => '965432109',
                'operador' => $opEntel,
                'domicilio' => 'CALLE LOS PINOS 567, MIRAFLORES',
                'domUbigeo' => '150122',
            ],
            [
                'paterno' => 'GUTIERREZ',
                'materno' => 'PALOMINO',
                'nombre' => 'ANA LUCIA',
                'sexo_id' => $sexoF,
                'docIdentidad' => '40567890',
                'fechaNac' => '1993-02-18',
                'ubigeo' => '150101',
                'email' => 'ana.gutierrez@yahoo.com',
                'telefono' => '954321098',
                'operador' => $opBitel,
                'domicilio' => 'AV. BENAVIDES 890, SURQUILLO',
                'domUbigeo' => '150141',
            ],
            [
                'paterno' => 'MENDOZA',
                'materno' => 'CCORIMANYA',
                'nombre' => 'PEDRO JOSE',
                'sexo_id' => $sexoM,
                'docIdentidad' => '50678901',
                'fechaNac' => '1982-09-30',
                'ubigeo' => '150101',
                'email' => 'pedro.mendoza@gmail.com',
                'telefono' => '943210987',
                'operador' => $opClaro,
                'domicilio' => 'PSJ. LAS FLORES 23, LA VICTORIA',
                'domUbigeo' => '150117',
            ],
            [
                'paterno' => 'TORRES',
                'materno' => 'SAAVEDRA',
                'nombre' => 'ROSA ISABEL',
                'sexo_id' => $sexoF,
                'docIdentidad' => '60789012',
                'fechaNac' => '1988-05-14',
                'ubigeo' => '150101',
                'email' => 'rtorres.edu@gmail.com',
                'telefono' => '932109876',
                'operador' => $opMovistar,
                'domicilio' => 'AV. UNIVERSITARIA 1200, LOS OLIVOS',
                'domUbigeo' => '150121',
            ],
            [
                'paterno' => 'VARGAS',
                'materno' => 'CONDORI',
                'nombre' => 'LUIS MIGUEL',
                'sexo_id' => $sexoM,
                'docIdentidad' => '70890123',
                'fechaNac' => '1975-12-08',
                'ubigeo' => '150101',
                'email' => 'lvargas1975@outlook.com',
                'telefono' => '921098765',
                'operador' => $opEntel,
                'domicilio' => 'JR. HUALLAGA 456, RIMAC',
                'domUbigeo' => '150130',
            ],
            [
                'paterno' => 'RAMIREZ',
                'materno' => 'ASTO',
                'nombre' => 'CARMEN ROSA',
                'sexo_id' => $sexoF,
                'docIdentidad' => '80901234',
                'fechaNac' => '1995-04-22',
                'ubigeo' => '150101',
                'email' => 'carmenramirez@gmail.com',
                'telefono' => '910987654',
                'operador' => $opBitel,
                'domicilio' => 'AV. COLONIAL 780, BREÑA',
                'domUbigeo' => '150104',
            ],
            [
                'paterno' => 'SOLIS',
                'materno' => 'AGUILAR',
                'nombre' => 'MARCO ANTONIO',
                'sexo_id' => $sexoM,
                'docIdentidad' => '91012345',
                'fechaNac' => '1980-08-17',
                'ubigeo' => '150101',
                'email' => 'marco.solis@gmail.com',
                'telefono' => '998765432',
                'operador' => $opClaro,
                'domicilio' => 'CALLE GRAU 321, BARRANCO',
                'domUbigeo' => '150103',
            ],
            [
                'paterno' => 'PAREDES',
                'materno' => 'LAZO',
                'nombre' => 'JESSICA PATRICIA',
                'sexo_id' => $sexoF,
                'docIdentidad' => '01123456',
                'fechaNac' => '1991-01-09',
                'ubigeo' => '150101',
                'email' => 'jessica.paredes@hotmail.com',
                'telefono' => '987654320',
                'operador' => $opMovistar,
                'domicilio' => 'AV. ANGAMOS 650, MIRAFLORES',
                'domUbigeo' => '150122',
            ],
            [
                'paterno' => 'CHAVEZ',
                'materno' => 'RIOS',
                'nombre' => 'ROBERTO CARLOS',
                'sexo_id' => $sexoM,
                'docIdentidad' => '12345678',
                'fechaNac' => '1983-06-03',
                'ubigeo' => '150101',
                'email' => 'rchavez.docente@gmail.com',
                'telefono' => '976543211',
                'operador' => $opEntel,
                'domicilio' => 'JR. AMAZONAS 99, SAN MARTIN DE PORRES',
                'domUbigeo' => '150132',
            ],
            [
                'paterno' => 'HUAMAN',
                'materno' => 'PILCO',
                'nombre' => 'GLADYS MILAGROS',
                'sexo_id' => $sexoF,
                'docIdentidad' => '23456789',
                'fechaNac' => '1987-10-11',
                'ubigeo' => '150101',
                'email' => 'gladys.huaman@gmail.com',
                'telefono' => '965432100',
                'operador' => $opBitel,
                'domicilio' => 'AV. PERU 1450, SAN MARTIN DE PORRES',
                'domUbigeo' => '150132',
            ],
            [
                'paterno' => 'LEON',
                'materno' => 'ESPINOZA',
                'nombre' => 'VICTOR HUGO',
                'sexo_id' => $sexoM,
                'docIdentidad' => '34567890',
                'fechaNac' => '1973-03-27',
                'ubigeo' => '150101',
                'email' => 'victor.leon@yahoo.com',
                'telefono' => '954321090',
                'operador' => $opClaro,
                'domicilio' => 'CALLE INDEPENDENCIA 567, PUEBLO LIBRE',
                'domUbigeo' => '150127',
            ],
            [
                'paterno' => 'CANO',
                'materno' => 'VERA',
                'nombre' => 'PATRICIA MILAGROS',
                'sexo_id' => $sexoF,
                'docIdentidad' => '45678901',
                'fechaNac' => '1996-09-15',
                'ubigeo' => '150101',
                'email' => 'pcanovera@gmail.com',
                'telefono' => '943210980',
                'operador' => $opMovistar,
                'domicilio' => 'AV. JAVIER PRADO ESTE 2300, SAN ISIDRO',
                'domUbigeo' => '150131',
            ],
            [
                'paterno' => 'DIAZ',
                'materno' => 'HURTADO',
                'nombre' => 'EDGAR FABIAN',
                'sexo_id' => $sexoM,
                'docIdentidad' => '56789012',
                'fechaNac' => '1979-07-20',
                'ubigeo' => '150101',
                'email' => 'edgar.diaz@gmail.com',
                'telefono' => '932109870',
                'operador' => $opEntel,
                'domicilio' => 'JR. CUSCO 789, LIMA CERCADO',
                'domUbigeo' => '150101',
            ],
        ];

        $now = now();

        foreach ($personas as $i => $data) {
            // ── Insertar persona ──────────────────────────────────────────────
            $personaId = DB::table('t_personas')->insertGetId([
                'pais_id' => $paisPE,
                'tipoDocIdentidad_id' => $dniId,
                'docIdentidad' => $data['docIdentidad'],
                'paterno' => $data['paterno'],
                'materno' => $data['materno'],
                'nombre' => $data['nombre'],
                'sexo_id' => $data['sexo_id'],
                'fechaNac' => $data['fechaNac'],
                'ubigeo' => $data['ubigeo'],
                'activo' => true,
                'created_by' => 1,
                'created_at' => $now,
            ]);

            // ── Insertar email ────────────────────────────────────────────────
            DB::table('t_emails')->insert([
                'persona_id' => $personaId,
                'email' => $data['email'],
                'personalInst' => 'P',              // P = personal
                'fechaInicio' => $data['fechaNac'],
                'created_by' => 1,
                'created_at' => $now,
            ]);

            // ── Insertar teléfono ─────────────────────────────────────────────
            DB::table('t_telefonos')->insert([
                'persona_id' => $personaId,
                'operador_id' => $data['operador'],
                'movilFijo' => 'M',              // M = móvil
                'codigoPais' => '+51',
                'numero' => $data['telefono'],
                'fechaInicio' => $data['fechaNac'],
                'created_by' => 1,
                'created_at' => $now,
            ]);

            // ── Insertar domicilio ────────────────────────────────────────────
            $zonaId = $zonas[$i % count($zonas)];
            DB::table('t_domicilios')->insert([
                'persona_id' => $personaId,
                'domicilio' => $data['domicilio'],
                'zona_id' => $zonaId,
                'ubigeo' => $data['domUbigeo'],
                'fechaInicio' => '2020-01-01',
                'created_by' => 1,
                'created_at' => $now,
            ]);
        }

        $this->command->info('✅ PersonasSeeder: 15 personas insertadas con email, teléfono y domicilio.');
    }
}
