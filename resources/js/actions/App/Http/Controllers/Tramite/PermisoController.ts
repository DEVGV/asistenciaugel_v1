import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Tramite\PermisoController::porTrabajador
* @see app/Http/Controllers/Tramite/PermisoController.php:24
* @route '/trabajadores/{trabajador}/permisos'
*/
export const porTrabajador = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

porTrabajador.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/permisos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porTrabajador
* @see app/Http/Controllers/Tramite/PermisoController.php:24
* @route '/trabajadores/{trabajador}/permisos'
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
* @see \App\Http\Controllers\Tramite\PermisoController::porTrabajador
* @see app/Http/Controllers/Tramite/PermisoController.php:24
* @route '/trabajadores/{trabajador}/permisos'
*/
porTrabajador.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porTrabajador
* @see app/Http/Controllers/Tramite/PermisoController.php:24
* @route '/trabajadores/{trabajador}/permisos'
*/
porTrabajador.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: porTrabajador.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porTrabajador
* @see app/Http/Controllers/Tramite/PermisoController.php:24
* @route '/trabajadores/{trabajador}/permisos'
*/
const porTrabajadorForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porTrabajador
* @see app/Http/Controllers/Tramite/PermisoController.php:24
* @route '/trabajadores/{trabajador}/permisos'
*/
porTrabajadorForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porTrabajador
* @see app/Http/Controllers/Tramite/PermisoController.php:24
* @route '/trabajadores/{trabajador}/permisos'
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
* @see \App\Http\Controllers\Tramite\PermisoController::porInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:31
* @route '/instituciones/{institucione}/permisos'
*/
export const porInstitucion = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porInstitucion.url(args, options),
    method: 'get',
})

porInstitucion.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/permisos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:31
* @route '/instituciones/{institucione}/permisos'
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
* @see \App\Http\Controllers\Tramite\PermisoController::porInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:31
* @route '/instituciones/{institucione}/permisos'
*/
porInstitucion.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:31
* @route '/instituciones/{institucione}/permisos'
*/
porInstitucion.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: porInstitucion.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:31
* @route '/instituciones/{institucione}/permisos'
*/
const porInstitucionForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:31
* @route '/instituciones/{institucione}/permisos'
*/
porInstitucionForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::porInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:31
* @route '/instituciones/{institucione}/permisos'
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
* @see \App\Http\Controllers\Tramite\PermisoController::altasPorInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
export const altasPorInstitucion = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: altasPorInstitucion.url(args, options),
    method: 'get',
})

altasPorInstitucion.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/permisos-altas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altasPorInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altasPorInstitucion.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return altasPorInstitucion.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altasPorInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altasPorInstitucion.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: altasPorInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altasPorInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altasPorInstitucion.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: altasPorInstitucion.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altasPorInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
const altasPorInstitucionForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altasPorInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altasPorInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altasPorInstitucionForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altasPorInstitucion.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altasPorInstitucion
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altasPorInstitucionForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altasPorInstitucion.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

altasPorInstitucion.form = altasPorInstitucionForm

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/permisos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::store
* @see app/Http/Controllers/Tramite/PermisoController.php:45
* @route '/permisos'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
export const validar = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validar.url(args, options),
    method: 'post',
})

validar.definition = {
    methods: ["post"],
    url: '/permisos/{expediente}/validar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
validar.url = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return validar.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
validar.post = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: validar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
const validarForm = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: validar.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::validar
* @see app/Http/Controllers/Tramite/PermisoController.php:53
* @route '/permisos/{expediente}/validar'
*/
validarForm.post = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: validar.url(args, options),
    method: 'post',
})

validar.form = validarForm

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
export const anular = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: anular.url(args, options),
    method: 'post',
})

anular.definition = {
    methods: ["post"],
    url: '/permisos/{expediente}/anular',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
anular.url = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
anular.post = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: anular.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
const anularForm = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: anular.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::anular
* @see app/Http/Controllers/Tramite/PermisoController.php:61
* @route '/permisos/{expediente}/anular'
*/
anularForm.post = (args: { expediente: string | number | { id: string | number } } | [expediente: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: anular.url(args, options),
    method: 'post',
})

anular.form = anularForm

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargarSustento
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
export const descargarSustento = (args: { documentoTram: string | number | { id: string | number } } | [documentoTram: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: descargarSustento.url(args, options),
    method: 'get',
})

descargarSustento.definition = {
    methods: ["get","head"],
    url: '/documentos-tram/{documentoTram}/descargar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargarSustento
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarSustento.url = (args: { documentoTram: string | number | { id: string | number } } | [documentoTram: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return descargarSustento.definition.url
            .replace('{documentoTram}', parsedArgs.documentoTram.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargarSustento
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarSustento.get = (args: { documentoTram: string | number | { id: string | number } } | [documentoTram: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: descargarSustento.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargarSustento
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarSustento.head = (args: { documentoTram: string | number | { id: string | number } } | [documentoTram: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: descargarSustento.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargarSustento
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
const descargarSustentoForm = (args: { documentoTram: string | number | { id: string | number } } | [documentoTram: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargarSustento.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargarSustento
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarSustentoForm.get = (args: { documentoTram: string | number | { id: string | number } } | [documentoTram: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargarSustento.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargarSustento
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarSustentoForm.head = (args: { documentoTram: string | number | { id: string | number } } | [documentoTram: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargarSustento.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

descargarSustento.form = descargarSustentoForm

const PermisoController = { porTrabajador, porInstitucion, altasPorInstitucion, store, validar, anular, descargarSustento }

export default PermisoController