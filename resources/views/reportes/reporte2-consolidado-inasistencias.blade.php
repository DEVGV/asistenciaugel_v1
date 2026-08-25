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
        body { font-family: Arial, Helvetica, sans-serif; font-size: 8pt; color: #000; }
        .header-title { text-align: center; font-size: 7pt; font-weight: bold; line-height: 1.3; margin-bottom: 6px; }
        .header-anexo { text-align: center; font-size: 9pt; font-weight: bold; margin-bottom: 2px; }
        .header-formato { text-align: center; font-size: 8pt; font-weight: bold; margin-bottom: 10px; }
        .info-table { width: 100%; margin-bottom: 8px; border: none; }
        .info-table td { font-size: 8pt; padding: 1px 0; border: none; vertical-align: bottom; }
        .info-label { font-weight: normal; }
        .info-line { border-bottom: 1px solid #000; display: inline-block; min-width: 200px; padding-bottom: 1px; }

        .main-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .main-table th, .main-table td {
            border: 1px solid #000;
            text-align: center;
            padding: 2px 3px;
            font-size: 7pt;
            vertical-align: middle;
        }
        .main-table th { background-color: #f5f5f5; font-weight: bold; }
        .main-table .col-nro { width: 25px; }
        .main-table .col-dni { width: 65px; }
        .main-table .col-nombre { width: 180px; text-align: left; padding-left: 4px; }
        .main-table .col-cargo { width: 90px; font-size: 6.5pt; }
        .main-table .col-condicion { width: 55px; }
        .main-table .col-jor { width: 30px; }
        .main-table .col-data { width: 50px; }
        .main-table .col-obs { width: 100px; text-align: left; padding-left: 3px; }
        .main-table .nombre-cell { text-align: left; padding-left: 4px; font-size: 6.5pt; }

        .nota-pie { font-size: 6.5pt; font-style: italic; margin-bottom: 15px; }
        .firma-section { text-align: center; margin-top: 40px; }
        .firma-linea { display: inline-block; width: 200px; border-top: 1px solid #000; text-align: center; padding-top: 2px; font-size: 8pt; }
    </style>
</head>
<body>
    <div class="header-title">
        NORMAS PARA EL REGISTRO Y CONTROL DE ASISTENCIA Y SU APLICACI&Oacute;N EN LA PLANILLA &Uacute;NICA DE PAGOS DE LOS PROFESORES Y AUXILIARES DE EDUCACI&Oacute;N, EN EL MARCO<br>
        DE LA LEY DE REFORMA MAGISTERIAL Y SU REGLAMENTO
    </div>
    <div class="header-anexo">ANEXO 04</div>
    <div class="header-formato">FORMATO 02: REPORTE CONSOLIDADO DE INASISTENCIAS, TARDANZAS Y PERMISOS SIN GOCE DE REMUNERACIONES</div>

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
                <th class="col-nro" rowspan="3">N&ordm;</th>
                <th class="col-dni" rowspan="3">DNI</th>
                <th class="col-nombre" rowspan="3">Apellidos y Nombres</th>
                <th class="col-cargo" rowspan="3">Cargo</th>
                <th class="col-condicion" rowspan="3">Condici&oacute;n</th>
                <th class="col-jor" rowspan="3">Jor.<br>Lab.</th>
                <th class="col-data" rowspan="2">Inasistencias</th>
                <th colspan="2">Tardanzas</th>
                <th colspan="2">Permisos SG</th>
                <th class="col-data" rowspan="2">Huelga/Paro</th>
                <th class="col-obs" rowspan="3">Observaciones</th>
            </tr>
            <tr>
                <th class="col-data">Horas</th>
                <th class="col-data">Minutos</th>
                <th class="col-data">Horas</th>
                <th class="col-data">Minutos</th>
            </tr>
            <tr>
                <th class="col-data">D&iacute;as</th>
                <th class="col-data">(*)</th>
                <th class="col-data">(*)</th>
                <th class="col-data">(*)</th>
                <th class="col-data">(*)</th>
                <th class="col-data">D&iacute;as</th>
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
                    <td class="col-data">{{ $trab['inasistencias_dias'] ?: '' }}</td>
                    <td class="col-data">{{ $trab['tardanzas_horas'] ?: '' }}</td>
                    <td class="col-data">{{ $trab['tardanzas_minutos'] ?: '' }}</td>
                    <td class="col-data">{{ $trab['permisos_sg_horas'] ?: '' }}</td>
                    <td class="col-data">{{ $trab['permisos_sg_minutos'] ?: '' }}</td>
                    <td class="col-data">{{ $trab['huelga_dias'] ?: '' }}</td>
                    <td class="col-obs">{{ $trab['observaciones'] }}</td>
                </tr>
            @empty
                <tr><td colspan="13" style="padding:10px;">Sin datos procesados.</td></tr>
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
                    <td class="col-data">&nbsp;</td>
                    <td class="col-data">&nbsp;</td>
                    <td class="col-data">&nbsp;</td>
                    <td class="col-data">&nbsp;</td>
                    <td class="col-data">&nbsp;</td>
                    <td class="col-data">&nbsp;</td>
                    <td class="col-obs">&nbsp;</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="nota-pie">(*) Hora y minuto cronol&oacute;gico</div>

    <div class="firma-section">
        <div class="firma-linea">Director</div>
    </div>
</body>
</html>
