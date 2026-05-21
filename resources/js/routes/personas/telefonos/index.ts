import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:18
* @route '/personas/{persona}/telefonos'
*/
export const store = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/personas/{persona}/telefonos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:18
* @route '/personas/{persona}/telefonos'
*/
store.url = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { persona: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { persona: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            persona: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        persona: typeof args.persona === 'object'
        ? args.persona.id
        : args.persona,
    }

    return store.definition.url
            .replace('{persona}', parsedArgs.persona.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:18
* @route '/personas/{persona}/telefonos'
*/
store.post = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:18
* @route '/personas/{persona}/telefonos'
*/
const storeForm = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:18
* @route '/personas/{persona}/telefonos'
*/
storeForm.post = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

const telefonos = {
    store: Object.assign(store, store),
}

export default telefonos