import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:19
* @route '/horarios-trabajador'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/horarios-trabajador',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:19
* @route '/horarios-trabajador'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:19
* @route '/horarios-trabajador'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:19
* @route '/horarios-trabajador'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:19
* @route '/horarios-trabajador'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:19
* @route '/horarios-trabajador'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:19
* @route '/horarios-trabajador'
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
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:36
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
export const show = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/horarios-trabajador/{horarioTrabajador}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:36
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
show.url = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { horarioTrabajador: args }
    }

    if (Array.isArray(args)) {
        args = {
            horarioTrabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        horarioTrabajador: args.horarioTrabajador,
    }

    return show.definition.url
            .replace('{horarioTrabajador}', parsedArgs.horarioTrabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:36
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
show.get = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:36
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
show.head = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:36
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
const showForm = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:36
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
showForm.get = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:36
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
showForm.head = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::porTrabajador
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:27
* @route '/trabajadores/{trabajador}/horarios'
*/
export const porTrabajador = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

porTrabajador.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/horarios',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::porTrabajador
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:27
* @route '/trabajadores/{trabajador}/horarios'
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
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::porTrabajador
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:27
* @route '/trabajadores/{trabajador}/horarios'
*/
porTrabajador.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::porTrabajador
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:27
* @route '/trabajadores/{trabajador}/horarios'
*/
porTrabajador.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: porTrabajador.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::porTrabajador
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:27
* @route '/trabajadores/{trabajador}/horarios'
*/
const porTrabajadorForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::porTrabajador
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:27
* @route '/trabajadores/{trabajador}/horarios'
*/
porTrabajadorForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: porTrabajador.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::porTrabajador
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:27
* @route '/trabajadores/{trabajador}/horarios'
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

const HorarioTrabajadorController = { index, show, porTrabajador }

export default HorarioTrabajadorController