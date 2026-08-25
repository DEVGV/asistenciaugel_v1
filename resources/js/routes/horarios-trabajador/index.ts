import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import manual from './manual'
/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:20
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
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:20
* @route '/horarios-trabajador'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:20
* @route '/horarios-trabajador'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:20
* @route '/horarios-trabajador'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:20
* @route '/horarios-trabajador'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:20
* @route '/horarios-trabajador'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::index
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:20
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
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:44
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
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:44
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
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:44
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
show.get = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:44
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
show.head = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:44
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
const showForm = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:44
* @route '/horarios-trabajador/{horarioTrabajador}'
*/
showForm.get = (args: { horarioTrabajador: string | number } | [horarioTrabajador: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::show
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:44
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

const horariosTrabajador = {
    index: Object.assign(index, index),
    show: Object.assign(show, show),
    manual: Object.assign(manual, manual),
}

export default horariosTrabajador