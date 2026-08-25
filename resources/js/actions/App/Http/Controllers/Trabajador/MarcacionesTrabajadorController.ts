import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::porTrabajador
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
export const porTrabajador = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

porTrabajador.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/marcaciones',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::porTrabajador
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
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
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::porTrabajador
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
porTrabajador.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::porTrabajador
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
porTrabajador.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: porTrabajador.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::porTrabajador
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
const porTrabajadorForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::porTrabajador
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
porTrabajadorForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::porTrabajador
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
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
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
export const asistenciaConsolidada = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: asistenciaConsolidada.url(args, options),
    method: 'get',
})

asistenciaConsolidada.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/asistencia-consolidada',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidada.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return asistenciaConsolidada.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidada.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: asistenciaConsolidada.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidada.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: asistenciaConsolidada.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
const asistenciaConsolidadaForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: asistenciaConsolidada.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidadaForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: asistenciaConsolidada.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidadaForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: asistenciaConsolidada.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

asistenciaConsolidada.form = asistenciaConsolidadaForm

const MarcacionesTrabajadorController = { porTrabajador, asistenciaConsolidada }

export default MarcacionesTrabajadorController