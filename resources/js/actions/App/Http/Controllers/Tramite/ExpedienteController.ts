import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/expedientes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::descargarDocumento
* @see app/Http/Controllers/Tramite/ExpedienteController.php:269
* @route '/documentos-tram/{documentoTram}/descargar'
*/
export const descargarDocumento = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: descargarDocumento.url(args, options),
    method: 'get',
})

descargarDocumento.definition = {
    methods: ["get","head"],
    url: '/documentos-tram/{documentoTram}/descargar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::descargarDocumento
* @see app/Http/Controllers/Tramite/ExpedienteController.php:269
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarDocumento.url = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { documentoTram: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { documentoTram: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            documentoTram: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        documentoTram: typeof args.documentoTram === 'object'
        ? args.documentoTram.id
        : args.documentoTram,
    }

    return descargarDocumento.definition.url
            .replace('{documentoTram}', parsedArgs.documentoTram.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::descargarDocumento
* @see app/Http/Controllers/Tramite/ExpedienteController.php:269
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarDocumento.get = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: descargarDocumento.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::descargarDocumento
* @see app/Http/Controllers/Tramite/ExpedienteController.php:269
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarDocumento.head = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: descargarDocumento.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::descargarDocumento
* @see app/Http/Controllers/Tramite/ExpedienteController.php:269
* @route '/documentos-tram/{documentoTram}/descargar'
*/
const descargarDocumentoForm = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargarDocumento.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::descargarDocumento
* @see app/Http/Controllers/Tramite/ExpedienteController.php:269
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarDocumentoForm.get = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargarDocumento.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::descargarDocumento
* @see app/Http/Controllers/Tramite/ExpedienteController.php:269
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarDocumentoForm.head = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargarDocumento.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

descargarDocumento.form = descargarDocumentoForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/expedientes',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
export const anular = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: anular.url(args, options),
    method: 'post',
})

anular.definition = {
    methods: ["post"],
    url: '/expedientes/{expediente}/anular',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
anular.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return anular.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
anular.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: anular.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
const anularForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: anular.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
anularForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: anular.url(args, options),
    method: 'post',
})

anular.form = anularForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalleJson
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
export const detalleJson = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalleJson.url(args, options),
    method: 'get',
})

detalleJson.definition = {
    methods: ["get","head"],
    url: '/api/expedientes/{expediente}/detalle',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalleJson
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalleJson.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return detalleJson.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalleJson
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalleJson.get = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalleJson.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalleJson
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalleJson.head = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detalleJson.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalleJson
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
const detalleJsonForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalleJson.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalleJson
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalleJsonForm.get = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalleJson.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalleJson
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalleJsonForm.head = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalleJson.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

detalleJson.form = detalleJsonForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porTrabajador
* @see app/Http/Controllers/Tramite/ExpedienteController.php:303
* @route '/api/trabajadores/{trabajador}/expedientes'
*/
export const porTrabajador = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

porTrabajador.definition = {
    methods: ["get","head"],
    url: '/api/trabajadores/{trabajador}/expedientes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porTrabajador
* @see app/Http/Controllers/Tramite/ExpedienteController.php:303
* @route '/api/trabajadores/{trabajador}/expedientes'
*/
porTrabajador.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { trabajador: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { trabajador: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
    }

    return porTrabajador.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porTrabajador
* @see app/Http/Controllers/Tramite/ExpedienteController.php:303
* @route '/api/trabajadores/{trabajador}/expedientes'
*/
porTrabajador.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porTrabajador
* @see app/Http/Controllers/Tramite/ExpedienteController.php:303
* @route '/api/trabajadores/{trabajador}/expedientes'
*/
porTrabajador.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: porTrabajador.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porTrabajador
* @see app/Http/Controllers/Tramite/ExpedienteController.php:303
* @route '/api/trabajadores/{trabajador}/expedientes'
*/
const porTrabajadorForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porTrabajador
* @see app/Http/Controllers/Tramite/ExpedienteController.php:303
* @route '/api/trabajadores/{trabajador}/expedientes'
*/
porTrabajadorForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porTrabajador
* @see app/Http/Controllers/Tramite/ExpedienteController.php:303
* @route '/api/trabajadores/{trabajador}/expedientes'
*/
porTrabajadorForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

porTrabajador.form = porTrabajadorForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::altasActivas
* @see app/Http/Controllers/Tramite/ExpedienteController.php:324
* @route '/api/trabajadores/{trabajador}/altas-activas'
*/
export const altasActivas = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: altasActivas.url(args, options),
    method: 'get',
})

altasActivas.definition = {
    methods: ["get","head"],
    url: '/api/trabajadores/{trabajador}/altas-activas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::altasActivas
* @see app/Http/Controllers/Tramite/ExpedienteController.php:324
* @route '/api/trabajadores/{trabajador}/altas-activas'
*/
altasActivas.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { trabajador: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { trabajador: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
    }

    return altasActivas.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::altasActivas
* @see app/Http/Controllers/Tramite/ExpedienteController.php:324
* @route '/api/trabajadores/{trabajador}/altas-activas'
*/
altasActivas.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: altasActivas.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::altasActivas
* @see app/Http/Controllers/Tramite/ExpedienteController.php:324
* @route '/api/trabajadores/{trabajador}/altas-activas'
*/
altasActivas.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: altasActivas.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::altasActivas
* @see app/Http/Controllers/Tramite/ExpedienteController.php:324
* @route '/api/trabajadores/{trabajador}/altas-activas'
*/
const altasActivasForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altasActivas.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::altasActivas
* @see app/Http/Controllers/Tramite/ExpedienteController.php:324
* @route '/api/trabajadores/{trabajador}/altas-activas'
*/
altasActivasForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altasActivas.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::altasActivas
* @see app/Http/Controllers/Tramite/ExpedienteController.php:324
* @route '/api/trabajadores/{trabajador}/altas-activas'
*/
altasActivasForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altasActivas.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

altasActivas.form = altasActivasForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porInstitucion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:310
* @route '/api/instituciones/{institucione}/expedientes'
*/
export const porInstitucion = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porInstitucion.url(args, options),
    method: 'get',
})

porInstitucion.definition = {
    methods: ["get","head"],
    url: '/api/instituciones/{institucione}/expedientes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porInstitucion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:310
* @route '/api/instituciones/{institucione}/expedientes'
*/
porInstitucion.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { institucione: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { institucione: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            institucione: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        institucione: typeof args.institucione === 'object'
        ? args.institucione.id
        : args.institucione,
    }

    return porInstitucion.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porInstitucion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:310
* @route '/api/instituciones/{institucione}/expedientes'
*/
porInstitucion.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porInstitucion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:310
* @route '/api/instituciones/{institucione}/expedientes'
*/
porInstitucion.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: porInstitucion.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porInstitucion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:310
* @route '/api/instituciones/{institucione}/expedientes'
*/
const porInstitucionForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porInstitucion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:310
* @route '/api/instituciones/{institucione}/expedientes'
*/
porInstitucionForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::porInstitucion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:310
* @route '/api/instituciones/{institucione}/expedientes'
*/
porInstitucionForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porInstitucion.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

porInstitucion.form = porInstitucionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::personalActivo
* @see app/Http/Controllers/Tramite/ExpedienteController.php:317
* @route '/api/instituciones/{institucione}/personal-activo'
*/
export const personalActivo = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: personalActivo.url(args, options),
    method: 'get',
})

personalActivo.definition = {
    methods: ["get","head"],
    url: '/api/instituciones/{institucione}/personal-activo',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::personalActivo
* @see app/Http/Controllers/Tramite/ExpedienteController.php:317
* @route '/api/instituciones/{institucione}/personal-activo'
*/
personalActivo.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { institucione: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { institucione: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            institucione: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        institucione: typeof args.institucione === 'object'
        ? args.institucione.id
        : args.institucione,
    }

    return personalActivo.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::personalActivo
* @see app/Http/Controllers/Tramite/ExpedienteController.php:317
* @route '/api/instituciones/{institucione}/personal-activo'
*/
personalActivo.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: personalActivo.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::personalActivo
* @see app/Http/Controllers/Tramite/ExpedienteController.php:317
* @route '/api/instituciones/{institucione}/personal-activo'
*/
personalActivo.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: personalActivo.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::personalActivo
* @see app/Http/Controllers/Tramite/ExpedienteController.php:317
* @route '/api/instituciones/{institucione}/personal-activo'
*/
const personalActivoForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: personalActivo.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::personalActivo
* @see app/Http/Controllers/Tramite/ExpedienteController.php:317
* @route '/api/instituciones/{institucione}/personal-activo'
*/
personalActivoForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: personalActivo.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::personalActivo
* @see app/Http/Controllers/Tramite/ExpedienteController.php:317
* @route '/api/instituciones/{institucione}/personal-activo'
*/
personalActivoForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: personalActivo.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

personalActivo.form = personalActivoForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
export const registroDirecto = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registroDirecto.url(options),
    method: 'post',
})

registroDirecto.definition = {
    methods: ["post"],
    url: '/api/registro-directo',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
registroDirecto.url = (options?: RouteQueryOptions) => {
    return registroDirecto.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
registroDirecto.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registroDirecto.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
const registroDirectoForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registroDirecto.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registroDirecto
* @see app/Http/Controllers/Tramite/ExpedienteController.php:82
* @route '/api/registro-directo'
*/
registroDirectoForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registroDirecto.url(options),
    method: 'post',
})

registroDirecto.form = registroDirectoForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::autorizar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:107
* @route '/api/expedientes/{expediente}/autorizar'
*/
export const autorizar = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: autorizar.url(args, options),
    method: 'post',
})

autorizar.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/autorizar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::autorizar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:107
* @route '/api/expedientes/{expediente}/autorizar'
*/
autorizar.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return autorizar.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::autorizar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:107
* @route '/api/expedientes/{expediente}/autorizar'
*/
autorizar.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: autorizar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::autorizar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:107
* @route '/api/expedientes/{expediente}/autorizar'
*/
const autorizarForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: autorizar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::autorizar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:107
* @route '/api/expedientes/{expediente}/autorizar'
*/
autorizarForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: autorizar.url(args, options),
    method: 'post',
})

autorizar.form = autorizarForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::rechazar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:120
* @route '/api/expedientes/{expediente}/rechazar'
*/
export const rechazar = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: rechazar.url(args, options),
    method: 'post',
})

rechazar.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/rechazar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::rechazar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:120
* @route '/api/expedientes/{expediente}/rechazar'
*/
rechazar.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return rechazar.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::rechazar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:120
* @route '/api/expedientes/{expediente}/rechazar'
*/
rechazar.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: rechazar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::rechazar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:120
* @route '/api/expedientes/{expediente}/rechazar'
*/
const rechazarForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: rechazar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::rechazar
* @see app/Http/Controllers/Tramite/ExpedienteController.php:120
* @route '/api/expedientes/{expediente}/rechazar'
*/
rechazarForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: rechazar.url(args, options),
    method: 'post',
})

rechazar.form = rechazarForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
export const registrarSuspension = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registrarSuspension.url(args, options),
    method: 'post',
})

registrarSuspension.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/suspension',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
registrarSuspension.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return registrarSuspension.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
registrarSuspension.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registrarSuspension.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
const registrarSuspensionForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registrarSuspension.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
registrarSuspensionForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registrarSuspension.url(args, options),
    method: 'post',
})

registrarSuspension.form = registrarSuspensionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
export const registrarJustificacion = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registrarJustificacion.url(args, options),
    method: 'post',
})

registrarJustificacion.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/justificacion',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
registrarJustificacion.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return registrarJustificacion.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
registrarJustificacion.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registrarJustificacion.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
const registrarJustificacionForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registrarJustificacion.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
registrarJustificacionForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registrarJustificacion.url(args, options),
    method: 'post',
})

registrarJustificacion.form = registrarJustificacionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
export const registrarIncapacidad = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registrarIncapacidad.url(args, options),
    method: 'post',
})

registrarIncapacidad.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/incapacidad',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
registrarIncapacidad.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return registrarIncapacidad.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
registrarIncapacidad.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registrarIncapacidad.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
const registrarIncapacidadForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registrarIncapacidad.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
registrarIncapacidadForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registrarIncapacidad.url(args, options),
    method: 'post',
})

registrarIncapacidad.form = registrarIncapacidadForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
export const registrarExoneracion = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registrarExoneracion.url(args, options),
    method: 'post',
})

registrarExoneracion.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/exoneracion',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
registrarExoneracion.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return registrarExoneracion.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
registrarExoneracion.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: registrarExoneracion.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
const registrarExoneracionForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registrarExoneracion.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::registrarExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
registrarExoneracionForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: registrarExoneracion.url(args, options),
    method: 'post',
})

registrarExoneracion.form = registrarExoneracionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
export const motivosSuspension = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosSuspension.url(options),
    method: 'get',
})

motivosSuspension.definition = {
    methods: ["get","head"],
    url: '/api/motivos-suspension',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspension.url = (options?: RouteQueryOptions) => {
    return motivosSuspension.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspension.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosSuspension.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspension.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motivosSuspension.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
const motivosSuspensionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosSuspension.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspensionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosSuspension.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosSuspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:216
* @route '/api/motivos-suspension'
*/
motivosSuspensionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosSuspension.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motivosSuspension.form = motivosSuspensionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
export const motivosIncapacidad = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosIncapacidad.url(options),
    method: 'get',
})

motivosIncapacidad.definition = {
    methods: ["get","head"],
    url: '/api/motivos-incapacidad',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidad.url = (options?: RouteQueryOptions) => {
    return motivosIncapacidad.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidad.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosIncapacidad.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidad.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motivosIncapacidad.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
const motivosIncapacidadForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosIncapacidad.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidadForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosIncapacidad.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosIncapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:230
* @route '/api/motivos-incapacidad'
*/
motivosIncapacidadForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosIncapacidad.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motivosIncapacidad.form = motivosIncapacidadForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
export const motivosJustificacion = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosJustificacion.url(options),
    method: 'get',
})

motivosJustificacion.definition = {
    methods: ["get","head"],
    url: '/api/motivos-justificacion',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacion.url = (options?: RouteQueryOptions) => {
    return motivosJustificacion.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacion.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosJustificacion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacion.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motivosJustificacion.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
const motivosJustificacionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosJustificacion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosJustificacion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosJustificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:243
* @route '/api/motivos-justificacion'
*/
motivosJustificacionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosJustificacion.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motivosJustificacion.form = motivosJustificacionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
export const motivosExoneracion = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosExoneracion.url(options),
    method: 'get',
})

motivosExoneracion.definition = {
    methods: ["get","head"],
    url: '/api/motivos-exoneracion',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracion.url = (options?: RouteQueryOptions) => {
    return motivosExoneracion.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracion.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: motivosExoneracion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracion.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: motivosExoneracion.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
const motivosExoneracionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosExoneracion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosExoneracion.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::motivosExoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:256
* @route '/api/motivos-exoneracion'
*/
motivosExoneracionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: motivosExoneracion.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

motivosExoneracion.form = motivosExoneracionForm

const ExpedienteController = { index, descargarDocumento, store, anular, detalleJson, porTrabajador, altasActivas, porInstitucion, personalActivo, registroDirecto, autorizar, rechazar, registrarSuspension, registrarJustificacion, registrarIncapacidad, registrarExoneracion, motivosSuspension, motivosIncapacidad, motivosJustificacion, motivosExoneracion }

export default ExpedienteController