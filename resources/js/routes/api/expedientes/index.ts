import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalle
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
export const detalle = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalle.url(args, options),
    method: 'get',
})

detalle.definition = {
    methods: ["get","head"],
    url: '/api/expedientes/{expediente}/detalle',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalle
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalle.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return detalle.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalle
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalle.get = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalle
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalle.head = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detalle.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalle
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
const detalleForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalle
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalleForm.get = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::detalle
* @see app/Http/Controllers/Tramite/ExpedienteController.php:276
* @route '/api/expedientes/{expediente}/detalle'
*/
detalleForm.head = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

detalle.form = detalleForm

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
* @see \App\Http\Controllers\Tramite\ExpedienteController::suspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
export const suspension = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: suspension.url(args, options),
    method: 'post',
})

suspension.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/suspension',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::suspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
suspension.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return suspension.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::suspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
suspension.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: suspension.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::suspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
const suspensionForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: suspension.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::suspension
* @see app/Http/Controllers/Tramite/ExpedienteController.php:138
* @route '/api/expedientes/{expediente}/suspension'
*/
suspensionForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: suspension.url(args, options),
    method: 'post',
})

suspension.form = suspensionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::justificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
export const justificacion = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: justificacion.url(args, options),
    method: 'post',
})

justificacion.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/justificacion',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::justificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
justificacion.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return justificacion.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::justificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
justificacion.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: justificacion.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::justificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
const justificacionForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: justificacion.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::justificacion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:157
* @route '/api/expedientes/{expediente}/justificacion'
*/
justificacionForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: justificacion.url(args, options),
    method: 'post',
})

justificacion.form = justificacionForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::incapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
export const incapacidad = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: incapacidad.url(args, options),
    method: 'post',
})

incapacidad.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/incapacidad',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::incapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
incapacidad.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return incapacidad.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::incapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
incapacidad.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: incapacidad.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::incapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
const incapacidadForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: incapacidad.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::incapacidad
* @see app/Http/Controllers/Tramite/ExpedienteController.php:176
* @route '/api/expedientes/{expediente}/incapacidad'
*/
incapacidadForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: incapacidad.url(args, options),
    method: 'post',
})

incapacidad.form = incapacidadForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::exoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
export const exoneracion = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: exoneracion.url(args, options),
    method: 'post',
})

exoneracion.definition = {
    methods: ["post"],
    url: '/api/expedientes/{expediente}/exoneracion',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::exoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
exoneracion.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return exoneracion.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::exoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
exoneracion.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: exoneracion.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::exoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
const exoneracionForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: exoneracion.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::exoneracion
* @see app/Http/Controllers/Tramite/ExpedienteController.php:195
* @route '/api/expedientes/{expediente}/exoneracion'
*/
exoneracionForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: exoneracion.url(args, options),
    method: 'post',
})

exoneracion.form = exoneracionForm

const expedientes = {
    detalle: Object.assign(detalle, detalle),
    autorizar: Object.assign(autorizar, autorizar),
    rechazar: Object.assign(rechazar, rechazar),
    suspension: Object.assign(suspension, suspension),
    justificacion: Object.assign(justificacion, justificacion),
    incapacidad: Object.assign(incapacidad, incapacidad),
    exoneracion: Object.assign(exoneracion, exoneracion),
}

export default expedientes