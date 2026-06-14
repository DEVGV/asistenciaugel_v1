import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
export const store = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/trabajadores/{trabajador}/altas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
store.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
store.post = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
const storeForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
storeForm.post = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

const altas = {
    store: Object.assign(store, store),
}

export default altas