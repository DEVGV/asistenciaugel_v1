<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 10mm 15mm 10mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 7pt; color: #000; }
        .header-title { text-align: center; font-size: 6.5pt; font-weight: bold; line-height: 1.3; margin-bottom: 6px; }
        .header-anexo { text-align: center; font-size: 8pt; font-weight: bold; margin-bottom: 2px; }
        .header-formato { text-align: center; font-size: 7.5pt; font-weight: bold; margin-bottom: 8px; }
        .info-table { width: 100%; margin-bottom: 6px; border: none; }
        .info-table td { font-size: 7pt; padding: 1px 0; border: none; vertical-align: bottom; }
        .info-label { font-weight: normal; }
        .info-line { border-bottom: 1px solid #000; display: inline-block; min-width: 180px; padding-bottom: 1px; }

        .main-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .main-table th, .main-table td {
            border: 1px solid #000;
            text-align: center;
            padding: 1px;
            font-size: 6pt;
            vertical-align: middle;
        }
        .main-table th { background-color: #f5f5f5; font-weight: bold; }
        .main-table .col-nro { width: 18px; }
        .main-table .col-dni { width: 50px; }
        .main-table .col-nombre { width: 120px; text-align: left; padding-left: 3px; }
        .main-table .col-cargo { width: 55px; font-size: 5.5pt; }
        .main-table .col-condicion { width: 38px; font-size: 5.5pt; }
        .main-table .col-jor { width: 22px; }
        .main-table .col-dia { width: 16px; font-size: 5.5pt; }
        .main-table .dia-header { font-size: 6pt; font-weight: bold; }
        .main-table .dia-semana { font-size: 5pt; font-weight: normal; }
        .main-table .nombre-cell { text-align: left; padding-left: 3px; font-size: 5.5pt; }

        .footer-section { margin-top: 15px; }
        .firma-section { text-align: right; margin-top: 30px; margin-right: 40px; }
        .firma-linea { display: inline-block; width: 200px; border-top: 1px solid #000; text-align: center; padding-top: 2px; font-size: 7pt; }
        .lugar-fecha { text-align: right; font-size: 7pt; margin-bottom: 5px; margin-right: 40px; }

        .leyenda { margin-top: 10px; font-size: 6pt; }
        .leyenda-title { font-weight: bold; font-size: 6.5pt; margin-bottom: 2px; }
        .leyenda table { border-collapse: collapse; }
        .leyenda td { padding: 0 5px 1px 0; font-size: 6pt; border: none; vertical-align: top; }
        .leyenda .sigla { font-weight: bold; width: 20px; }
    </style>
</head>
<body>
    <div class="header-title">
        NORMAS PARA EL REGISTRO Y CONTROL DE ASISTENCIA Y SU APLICACI&Oacute;N EN LA PLANILLA &Uacute;NICA DE PAGOS DE LOS PROFESORES Y AUXILIARES DE EDUCACI&Oacute;N, EN EL MARCO<br>
        DE LA LEY DE REFORMA MAGISTERIAL Y SU REGLAMENTO
    </div>
    <div class="header-anexo">ANEXO 03</div>
    <div class="header-formato">FORMATO 01: REPORTE DE ASISTENCIA DETALLADO</div>

    <table class="info-table">
        <tr>
            <td style="width:50%"><span class="info-label">DRE/UGEL:</span> <span class="info-line">{{ $dreUgel }}</span></td>
            <td style="width:50%"><span class="info-label">PERIODO(mes/a&ntilde;o):</span> <span class="info-line">{{ $mesNombre }}/{{ $anio }}</span></td>
        </tr>
        <tr>
            <td><span class="info-label">I.E.:</span> <span class="info-line">{{ $ieNombre }}</span></td>
            <td><span class="info-label">Turno:</span> <span class="info-line">{{ $turno }}</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="info-label">Nivel/Modalidad Educativa:</span> <span class="info-line">{{ $nivelModalidad }}</span></td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th class="col-nro" rowspan="2">N&ordm;</th>
                <th class="col-dni" rowspan="2">DNI</th>
                <th class="col-nombre" rowspan="2">Apellidos y Nombres</th>
                <th class="col-cargo" rowspan="2">Cargo</th>
                <th class="col-condicion" rowspan="2">Condici&oacute;n</th>
                <th class="col-jor" rowspan="2">Jor.<br>Lab.</th>
                <th colspan="{{ $diasEnMes }}">DIAS CALENDARIO</th>
            </tr>
            <tr>
                @for($d = 1; $d <= $diasEnMes; $d++)
                    <th class="col-dia">
                        <div class="dia-header">{{ $d }}</div>
                        <div class="dia-semana">{{ $diasSemana[$d] }}</div>
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @forelse($trabajadores as $i => $trab)
                <tr>
                    <td class="col-nro">{{ $i + 1 }}</td>
                    <td class="col-dni">{{ $trab['dni'] }}</td>
                    <td class="nombre-cell">{{ $trab['nombre'] }}</td>
                    <td class="col-cargo">{{ $trab['cargo'] }}</td>
                    <td class="col-condicion">{{ $trab['condicion'] }}</td>
                    <td class="col-jor">{{ $trab['jorLab'] }}</td>
                    @for($d = 1; $d <= $diasEnMes; $d++)
                        <td class="col-dia">{{ $trab['dias'][$d] ?? '' }}</td>
                    @endfor
                </tr>
            @empty
                <tr><td colspan="{{ 6 + $diasEnMes }}" style="padding:10px;">Sin datos procesados.</td></tr>
            @endforelse
            {{-- Filas vacías para completar el formato --}}
            @for($r = count($trabajadores); $r < max(count($trabajadores), 10); $r++)
                <tr>
                    <td class="col-nro">&nbsp;</td>
                    <td class="col-dni">&nbsp;</td>
                    <td class="nombre-cell">&nbsp;</td>
                    <td class="col-cargo">&nbsp;</td>
                    <td class="col-condicion">&nbsp;</td>
                    <td class="col-jor">&nbsp;</td>
                    @for($d = 1; $d <= $diasEnMes; $d++)
                        <td class="col-dia">&nbsp;</td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="lugar-fecha">Lugar y Fecha: ____________________________</div>

    <div class="firma-section">
        <div class="firma-linea">Director</div>
    </div>

    <div class="leyenda">
        <div class="leyenda-title">LEYENDA:</div>
        <table>
            <tr><td class="sigla">A</td><td>D&iacute;a laborado</td></tr>
            <tr><td class="sigla">I</td><td>Inasistencia Injustificada</td></tr>
            <tr><td class="sigla">3T</td><td>Tercera tardanza, considerada como inasistencia injustificada</td></tr>
            <tr><td class="sigla">J</td><td>Inasistencia justificada (licencia, permiso, vacaciones)</td></tr>
            <tr><td class="sigla">L</td><td>Licencia sin goce de remuneraciones</td></tr>
            <tr><td class="sigla">P</td><td>Permiso sin goce de remuneraciones</td></tr>
            <tr><td class="sigla">T</td><td>Tardanza</td></tr>
            <tr><td class="sigla">H</td><td>Huelga o paro</td></tr>
        </table>
    </div>
</body>
</html>
