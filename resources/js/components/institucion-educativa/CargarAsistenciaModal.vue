<script setup lang="ts">
import {
    Upload,
    Download,
    FileSpreadsheet,
    CheckCircle2,
    Trash2,
    Loader2,
    Info,
    Calendar,
    ArrowRight,
    AlertTriangle,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';

defineProps<{
    show: boolean;
    localNombre?: string;
    relojNombre?: string;
    relojId: number | null;
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'close'): void;
    (e: 'success', count: number): void;
}>();

// State variables
const fileInput = ref<HTMLInputElement | null>(null);
const selectedFile = ref<File | null>(null);
const isDragOver = ref(false);
const isUploading = ref(false);
const isSuccess = ref(false);
const errorGeneral = ref('');

// Form Options
const overwriteExisting = ref(false);

// Resultado real del servidor
const resultado = ref<{
    message: string;
    insertados: number;
    duplicados: number;
    errores: Record<number, string>;
    preview: Array<{ dni: string; nombre: string; fecha: string; tipo: string }>;
    resumen: {
        totalFilas: number;
        trabajadoresAsociados: number;
        sinCoincidencia: number;
        fechaMin: string | null;
        fechaMax: string | null;
    };
} | null>(null);

const erroresArray = computed(() => {
    if (!resultado.value?.errores) return [];
    return Object.entries(resultado.value.errores).map(([fila, msg]) => ({
        fila: Number(fila),
        mensaje: msg as string,
    }));
});

const formattedFileSize = computed(() => {
    if (!selectedFile.value) {
        return '';
    }

    const bytes = selectedFile.value.size;

    if (bytes === 0) {
        return '0 Bytes';
    }

    const k = 1024;
    const dm = 2;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
});

function onUpdateOpen(value: boolean) {
    emit('update:show', value);

    if (!value) {
        emit('close');
        resetState();
    }
}

function handleDragOver(e: DragEvent) {
    e.preventDefault();
    isDragOver.value = true;
}

function handleDragLeave() {
    isDragOver.value = false;
}

function handleDrop(e: DragEvent) {
    e.preventDefault();
    isDragOver.value = false;

    if (e.dataTransfer?.files && e.dataTransfer.files.length > 0) {
        const file = e.dataTransfer.files[0];

        if (isValidExcel(file)) {
            selectedFile.value = file;
        } else {
            alert('Por favor seleccione un archivo Excel (.xlsx, .xls)');
        }
    }
}

function selectFile() {
    fileInput.value?.click();
}

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;

    if (target.files && target.files.length > 0) {
        const file = target.files[0];

        if (isValidExcel(file)) {
            selectedFile.value = file;
        } else {
            alert('Por favor seleccione un archivo Excel (.xlsx, .xls)');
        }
    }
}

function isValidExcel(file: File): boolean {
    const extension = file.name.split('.').pop()?.toLowerCase();

    return extension === 'xlsx' || extension === 'xls';
}

function removeFile() {
    selectedFile.value = null;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}

function resetState() {
    selectedFile.value = null;
    isUploading.value = false;
    isSuccess.value = false;
    errorGeneral.value = '';
    resultado.value = null;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}

function downloadTemplate() {
    const xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>\n<?mso-application progid="Excel.Sheet"?>';
    const workbook = `
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
 <Styles>
  <Style ss:ID="header">
   <Font ss:Bold="1" ss:Size="11"/>
   <Interior ss:Color="#D9E2F3" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="example">
   <Font ss:Color="#808080" ss:Italic="1"/>
  </Style>
  <Style ss:ID="instruction">
   <Font ss:Color="#C00000" ss:Bold="1" ss:Size="10"/>
   <Interior ss:Color="#FFF2CC" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="auto">
   <Font ss:Color="#2E75B6" ss:Italic="1" ss:Size="9"/>
   <Interior ss:Color="#E2EFDA" ss:Pattern="Solid"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Marcaciones">
  <Table>
   <Column ss:Width="120"/>
   <Column ss:Width="200"/>
   <Row>
    <Cell ss:StyleID="header"><Data ss:Type="String">DNI</Data></Cell>
    <Cell ss:StyleID="header"><Data ss:Type="String">Fecha y Hora</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="example"><Data ss:Type="String">45892110</Data></Cell>
    <Cell ss:StyleID="example"><Data ss:Type="String">2026-07-01 07:54:12</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="example"><Data ss:Type="String">45892110</Data></Cell>
    <Cell ss:StyleID="example"><Data ss:Type="String">2026-07-01 13:00:00</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="example"><Data ss:Type="String">70213345</Data></Cell>
    <Cell ss:StyleID="example"><Data ss:Type="String">2026-07-01 08:02:45</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="example"><Data ss:Type="String">70213345</Data></Cell>
    <Cell ss:StyleID="example"><Data ss:Type="String">2026-07-01 13:05:00</Data></Cell>
   </Row>
  </Table>
 </Worksheet>
 <Worksheet ss:Name="Instrucciones">
  <Table>
   <Column ss:Width="180"/>
   <Column ss:Width="160"/>
   <Column ss:Width="350"/>
   <Row>
    <Cell ss:StyleID="header"><Data ss:Type="String">Columna Excel</Data></Cell>
    <Cell ss:StyleID="header"><Data ss:Type="String">Campo en BD</Data></Cell>
    <Cell ss:StyleID="header"><Data ss:Type="String">Descripcion</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">DNI</Data></Cell>
    <Cell><Data ss:Type="String">trabajador_id</Data></Cell>
    <Cell><Data ss:Type="String">Nro. de documento de identidad del trabajador (persona.docIdentidad). Se usa para resolver el trabajador_id. Obligatorio.</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Fecha y Hora</Data></Cell>
    <Cell><Data ss:Type="String">fechaMarcacion</Data></Cell>
    <Cell><Data ss:Type="String">Timestamp de la marcacion en formato AAAA-MM-DD HH:MM:SS (ej: 2026-07-01 07:54:12). Obligatorio.</Data></Cell>
   </Row>
   <Row><Cell></Cell></Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">CAMPOS AUTOMATICOS</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Campo en BD</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Como se genera</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">(automatico)</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">altaTrabajador_id</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Se resuelve desde el alta activa del trabajador en la IE.</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">(automatico)</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">localMarcacion_id</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Se asigna segun el local desde donde se carga el reporte.</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">(automatico)</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">reloj_id</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Se asigna segun el reloj biometrico seleccionado al cargar.</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">(automatico)</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">codigo</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Codigo unico generado por el sistema (prefijo MAR).</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">(automatico)</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">fechaRegistro</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Fecha/hora del momento de la importacion.</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">(automatico)</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">medioMarcacion</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Se establece como 'R' (Reloj biometrico) al importar desde Excel.</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">(automatico)</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">procesado</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">Se establece como false (pendiente de consolidacion).</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="auto"><Data ss:Type="String">(automatico)</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">tipo</Data></Cell>
    <Cell ss:StyleID="auto"><Data ss:Type="String">E = Entrada, S = Salida. Se determina automaticamente segun orden cronologico.</Data></Cell>
   </Row>
   <Row><Cell></Cell></Row>
   <Row>
    <Cell ss:StyleID="instruction"><Data ss:Type="String">IMPORTANTE</Data></Cell>
    <Cell ss:StyleID="instruction"></Cell>
    <Cell ss:StyleID="instruction"><Data ss:Type="String">Elimine las filas de ejemplo (en gris) antes de cargar. Solo llene las 2 columnas de la hoja Marcaciones (DNI y Fecha y Hora).</Data></Cell>
   </Row>
  </Table>
 </Worksheet>
</Workbook>`;

    const blob = new Blob([xmlHeader + workbook], {
        type: 'application/vnd.ms-excel',
    });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'plantilla_marcaciones.xls';
    a.click();
    URL.revokeObjectURL(url);
}

async function startUpload(relojId: number | null) {
    if (!selectedFile.value || !relojId) {
        return;
    }

    isUploading.value = true;
    errorGeneral.value = '';
    resultado.value = null;

    const formData = new FormData();
    formData.append('archivo', selectedFile.value);
    formData.append('reloj_id', String(relojId));
    formData.append('overwrite', overwriteExisting.value ? '1' : '0');

    try {
        const res = await fetch('/marcaciones/cargar-excel', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':
                    (
                        document.querySelector(
                            'meta[name="csrf-token"]',
                        ) as HTMLMetaElement
                    )?.content ?? '',
                Accept: 'application/json',
            },
            body: formData,
        });

        const data = await res.json();

        resultado.value = data;

        if (res.ok && data.insertados > 0) {
            isSuccess.value = true;
            emit('success', data.insertados);
        } else if (!res.ok) {
            // Errores de validación de Laravel (422 sin filas válidas)
            if (data.errors) {
                // Errores de FormRequest (campo inválido)
                const msgs = Object.values(data.errors).flat();
                errorGeneral.value = (msgs as string[]).join('. ');
            } else {
                errorGeneral.value = data.message ?? 'Error al procesar el archivo.';
                // Mostrar resultado parcial si hay errores por fila
                if (data.errores && Object.keys(data.errores).length > 0) {
                    isSuccess.value = true; // mostrar la vista de resultado (con errores)
                }
            }
        } else {
            // res.ok pero 0 insertados — mostrar resultado
            isSuccess.value = true;
        }
    } catch {
        errorGeneral.value = 'Error de conexión al procesar el archivo.';
    } finally {
        isUploading.value = false;
    }
}
</script>

<template>
    <Dialog :open="show" @update:open="onUpdateOpen">
        <DialogContent
            class="flex max-h-[90vh] w-full max-w-xl flex-col gap-0 overflow-hidden p-0 font-sans duration-300"
        >
            <!-- Header -->
            <DialogHeader
                class="sticky top-0 z-10 shrink-0 border-b bg-background px-6 pt-6 pb-5"
            >
                <DialogTitle
                    class="flex items-center gap-2 text-xl font-semibold tracking-tight text-foreground"
                >
                    <FileSpreadsheet class="h-5 w-5 text-emerald-600" />
                    Cargar Registro de Asistencia
                </DialogTitle>
                <DialogDescription class="mt-1 text-sm text-muted-foreground">
                    Cargue el reporte de asistencia (Excel) correspondiente al
                    reloj
                    <strong>{{ relojNombre || 'Seleccionado' }}</strong> (Local:
                    <strong>{{ localNombre || 'Sin nombre' }}</strong
                    >).
                </DialogDescription>
            </DialogHeader>

            <!-- Main Content Area -->
            <div class="flex-1 space-y-5 overflow-y-auto p-6 text-sm">
                <!-- State 1: Uploading Progress -->
                <div
                    v-if="isUploading"
                    class="flex flex-col items-center justify-center space-y-4 py-10"
                >
                    <Loader2 class="h-10 w-10 animate-spin text-emerald-600" />
                    <div class="text-center">
                        <p class="font-medium text-foreground">
                            Procesando archivo...
                        </p>
                        <p class="mt-1 text-xs text-muted-foreground">
                            Analizando estructura del reporte y vinculando con
                            trabajadores
                        </p>
                    </div>
                </div>

                <!-- State 2: Success / Result -->
                <div v-else-if="isSuccess && resultado" class="space-y-5">
                    <!-- Success banner -->
                    <div
                        v-if="resultado.insertados > 0"
                        class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/50 dark:bg-emerald-950/20"
                    >
                        <CheckCircle2
                            class="h-8 w-8 shrink-0 text-emerald-600"
                        />
                        <div>
                            <h4
                                class="font-semibold text-emerald-900 dark:text-emerald-400"
                            >
                                {{ resultado.message }}
                            </h4>
                            <p
                                v-if="resultado.duplicados > 0"
                                class="mt-0.5 text-xs text-emerald-700 dark:text-emerald-500"
                            >
                                {{ resultado.duplicados }} marcaciones
                                duplicadas omitidas.
                            </p>
                        </div>
                    </div>

                    <!-- No insertions banner -->
                    <div
                        v-else
                        class="flex items-center gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/50 dark:bg-amber-950/20"
                    >
                        <AlertTriangle
                            class="h-8 w-8 shrink-0 text-amber-600"
                        />
                        <div>
                            <h4
                                class="font-semibold text-amber-900 dark:text-amber-400"
                            >
                                No se insertaron marcaciones
                            </h4>
                            <p
                                class="mt-0.5 text-xs text-amber-700 dark:text-amber-500"
                            >
                                {{ resultado.message }}
                            </p>
                        </div>
                    </div>

                    <!-- Statistics grid -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-lg border bg-card p-3 text-center">
                            <span
                                class="mb-0.5 block text-xs text-muted-foreground"
                                >Filas Procesadas</span
                            >
                            <span class="text-lg font-bold text-foreground">{{
                                resultado.resumen.totalFilas
                            }}</span>
                        </div>
                        <div class="rounded-lg border bg-card p-3 text-center">
                            <span
                                class="mb-0.5 block text-xs text-muted-foreground"
                                >Trabajadores Asoc.</span
                            >
                            <span class="text-lg font-bold text-foreground">{{
                                resultado.resumen.trabajadoresAsociados
                            }}</span>
                        </div>
                        <div class="rounded-lg border bg-card p-3 text-center">
                            <span
                                class="mb-0.5 block text-xs text-muted-foreground"
                                >Sin Coincidencias</span
                            >
                            <span
                                class="text-lg font-bold"
                                :class="
                                    resultado.resumen.sinCoincidencia > 0
                                        ? 'text-amber-600'
                                        : 'text-emerald-600'
                                "
                                >{{ resultado.resumen.sinCoincidencia }}</span
                            >
                        </div>
                    </div>

                    <!-- Date range info -->
                    <div
                        v-if="
                            resultado.resumen.fechaMin &&
                            resultado.resumen.fechaMax
                        "
                        class="flex items-center justify-between rounded-lg border bg-muted/20 p-3 text-xs"
                    >
                        <span
                            class="flex items-center gap-1.5 font-medium text-muted-foreground"
                        >
                            <Calendar
                                class="h-3.5 w-3.5 text-muted-foreground"
                            />
                            Rango de Fechas:
                        </span>
                        <span
                            class="flex items-center gap-1 font-semibold text-foreground"
                        >
                            {{ resultado.resumen.fechaMin }}
                            <ArrowRight
                                class="inline h-3 w-3 text-muted-foreground"
                            />
                            {{ resultado.resumen.fechaMax }}
                        </span>
                    </div>

                    <!-- Preview Table -->
                    <div
                        v-if="resultado.preview.length > 0"
                        class="space-y-2"
                    >
                        <span
                            class="block text-xs font-semibold text-muted-foreground"
                            >Vista previa de registros:</span
                        >
                        <div class="overflow-x-auto rounded-md border bg-card">
                            <table
                                class="w-full border-collapse text-left text-xs"
                            >
                                <thead>
                                    <tr class="border-b bg-muted/40">
                                        <th class="p-2 font-semibold">
                                            DNI/Cod.
                                        </th>
                                        <th class="p-2 font-semibold">
                                            Trabajador
                                        </th>
                                        <th class="p-2 font-semibold">
                                            Fecha y Hora
                                        </th>
                                        <th
                                            class="p-2 text-right font-semibold"
                                        >
                                            Tipo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(row, idx) in resultado.preview"
                                        :key="idx"
                                        class="border-b last:border-0 hover:bg-muted/20"
                                    >
                                        <td
                                            class="p-2 font-mono text-muted-foreground"
                                        >
                                            {{ row.dni }}
                                        </td>
                                        <td
                                            class="p-2 font-medium text-foreground"
                                        >
                                            {{ row.nombre }}
                                        </td>
                                        <td class="p-2 text-muted-foreground">
                                            {{ row.fecha }}
                                        </td>
                                        <td class="p-2 text-right">
                                            <span
                                                class="inline-block rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400"
                                                >{{ row.tipo }}</span
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Errores por fila -->
                    <div
                        v-if="erroresArray.length > 0"
                        class="space-y-2"
                    >
                        <span
                            class="block text-xs font-semibold text-destructive"
                            >Errores encontrados ({{ erroresArray.length }}):</span
                        >
                        <div
                            class="max-h-40 overflow-y-auto rounded-md border border-destructive/20 bg-destructive/5 p-3"
                        >
                            <ul class="space-y-1 text-xs text-destructive">
                                <li
                                    v-for="err in erroresArray"
                                    :key="err.fila"
                                >
                                    <span class="font-semibold"
                                        >Fila {{ err.fila }}:</span
                                    >
                                    {{ err.mensaje }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- State 3: Upload and Selection Screen -->
                <div v-else class="space-y-4">
                    <!-- Error general -->
                    <div
                        v-if="errorGeneral"
                        class="flex gap-2.5 rounded-lg border border-destructive/30 bg-destructive/5 p-3 text-destructive"
                    >
                        <AlertTriangle class="mt-0.5 h-4 w-4 shrink-0" />
                        <p class="text-xs font-medium">{{ errorGeneral }}</p>
                    </div>

                    <!-- File Drag and Drop Zone -->
                    <div
                        @dragover="handleDragOver"
                        @dragleave="handleDragLeave"
                        @drop="handleDrop"
                        @click="selectFile"
                        :class="[
                            'flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed p-8 text-center transition-all duration-200',
                            isDragOver
                                ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/10'
                                : 'border-muted-foreground/20 hover:border-emerald-500/50 hover:bg-muted/10',
                        ]"
                    >
                        <input
                            ref="fileInput"
                            type="file"
                            accept=".xlsx, .xls"
                            class="hidden"
                            @change="handleFileChange"
                        />

                        <!-- Icon & Helper text -->
                        <div
                            v-if="!selectedFile"
                            class="flex flex-col items-center space-y-3"
                        >
                            <div
                                class="rounded-full bg-emerald-50 p-3 text-emerald-600 dark:bg-emerald-950/30"
                            >
                                <Upload class="h-6 w-6" />
                            </div>
                            <div>
                                <p class="font-medium text-foreground">
                                    Seleccionar reporte de reloj
                                </p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    Arrastre el archivo Excel aqui, o haga clic
                                    para buscar
                                </p>
                            </div>
                            <span
                                class="rounded bg-muted px-2 py-0.5 text-[10px] tracking-wider text-muted-foreground uppercase"
                                >Formato: .xlsx, .xls (max 10 MB)</span
                            >
                        </div>

                        <!-- Selected File View -->
                        <div
                            v-else
                            class="flex w-full items-center justify-between rounded-lg border border-emerald-200 bg-card p-3 dark:border-emerald-900/50"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="rounded bg-emerald-100 p-2 text-emerald-600 dark:bg-emerald-950"
                                >
                                    <FileSpreadsheet class="h-6 w-6" />
                                </div>
                                <div
                                    class="max-w-[280px] text-left sm:max-w-[340px]"
                                >
                                    <p
                                        class="truncate font-medium text-foreground"
                                        :title="selectedFile.name"
                                    >
                                        {{ selectedFile.name }}
                                    </p>
                                    <p
                                        class="mt-0.5 text-xs text-muted-foreground"
                                    >
                                        {{ formattedFileSize }}
                                    </p>
                                </div>
                            </div>
                            <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                class="h-8 w-8 p-0 text-muted-foreground hover:text-destructive"
                                @click.stop="removeFile"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>

                    <!-- Import options -->
                    <div class="space-y-3 rounded-lg border bg-muted/30 p-4">
                        <h4
                            class="text-xs font-semibold tracking-wider text-foreground uppercase"
                        >
                            Opciones de Importacion
                        </h4>

                        <div class="space-y-3 text-xs">
                            <!-- Download template -->
                            <div
                                class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center"
                            >
                                <div class="space-y-0.5">
                                    <Label
                                        class="font-medium text-muted-foreground"
                                        >Plantilla de carga:</Label
                                    >
                                    <p
                                        class="text-[10px] text-muted-foreground"
                                    >
                                        Descargue el formato con las columnas
                                        requeridas y filas de ejemplo.
                                    </p>
                                </div>
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    class="gap-1.5 border-emerald-300 text-emerald-700 hover:bg-emerald-50 dark:border-emerald-800 dark:text-emerald-400 dark:hover:bg-emerald-950/20"
                                    @click.stop="downloadTemplate"
                                >
                                    <Download class="h-3.5 w-3.5" />
                                    Descargar Plantilla
                                </Button>
                            </div>

                            <!-- Overwrite toggle -->
                            <div class="flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <Label
                                        class="font-medium text-muted-foreground"
                                        >Sobrescribir marcaciones
                                        existentes:</Label
                                    >
                                    <p
                                        class="text-[10px] text-muted-foreground"
                                    >
                                        Si ya existen marcaciones en las mismas
                                        fechas y horas, se actualizaran.
                                    </p>
                                </div>
                                <input
                                    type="checkbox"
                                    v-model="overwriteExisting"
                                    class="h-4 w-4 rounded border-input text-emerald-600 focus:ring-emerald-500"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Informative Note -->
                    <div
                        class="flex gap-2.5 rounded-lg border border-amber-200 bg-amber-50/50 p-3 text-amber-800 dark:border-amber-900/30 dark:bg-amber-950/10 dark:text-amber-400"
                    >
                        <Info class="mt-0.5 h-4 w-4 shrink-0 text-amber-600" />
                        <div class="space-y-1 text-xs">
                            <p class="font-semibold">
                                Requerimientos del archivo:
                            </p>
                            <p>
                                El Excel debe contener las columnas:
                                <span class="font-semibold">DNI</span> y
                                <span class="font-semibold">Fecha y Hora</span>
                                (formato AAAA-MM-DD HH:MM:SS). El tipo de
                                marcacion, reloj, local y demas campos se
                                asignan automaticamente. Descargue la plantilla
                                como guia.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <DialogFooter
                class="sticky bottom-0 z-10 flex shrink-0 flex-row justify-between rounded-b-xl border-t bg-muted/30 p-4 sm:justify-between"
            >
                <Button
                    v-if="isSuccess"
                    type="button"
                    variant="outline"
                    class="w-full"
                    @click="onUpdateOpen(false)"
                >
                    Entendido
                </Button>
                <template v-else>
                    <Button
                        type="button"
                        variant="outline"
                        @click="onUpdateOpen(false)"
                        :disabled="isUploading"
                    >
                        Cancelar
                    </Button>
                    <Button
                        type="button"
                        class="bg-emerald-600 text-white hover:bg-emerald-700"
                        :disabled="!selectedFile || isUploading"
                        @click="startUpload(relojId)"
                    >
                        <Upload class="mr-2 h-4 w-4" />
                        Cargar Reporte
                    </Button>
                </template>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
