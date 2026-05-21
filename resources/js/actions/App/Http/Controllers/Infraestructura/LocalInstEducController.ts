import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::store
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:18
* @route '/instituciones/{institucione}/locales-ie'
*/
export const store = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/locales-ie',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::store
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:18
* @route '/instituciones/{institucione}/locales-ie'
*/
store.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::store
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:18
* @route '/instituciones/{institucione}/locales-ie'
*/
store.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::store
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:18
* @route '/instituciones/{institucione}/locales-ie'
*/
const storeForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::store
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:18
* @route '/instituciones/{institucione}/locales-ie'
*/
storeForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::destroy
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:31
* @route '/locales-ie/{localesIe}'
*/
export const destroy = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/locales-ie/{localesIe}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::destroy
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:31
* @route '/locales-ie/{localesIe}'
*/
destroy.url = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { localesIe: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { localesIe: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            localesIe: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        localesIe: typeof args.localesIe === 'object'
        ? args.localesIe.id
        : args.localesIe,
    }

    return destroy.definition.url
            .replace('{localesIe}', parsedArgs.localesIe.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::destroy
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:31
* @route '/locales-ie/{localesIe}'
*/
destroy.delete = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::destroy
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:31
* @route '/locales-ie/{localesIe}'
*/
const destroyForm = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalInstEducController::destroy
* @see app/Http/Controllers/Infraestructura/LocalInstEducController.php:31
* @route '/locales-ie/{localesIe}'
*/
destroyForm.delete = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const LocalInstEducController = { store, destroy }

export default LocalInstEducController