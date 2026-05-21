import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import relojes from './relojes'
import marcacionesLocal from './marcaciones-local'
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

const localesIe = {
    destroy: Object.assign(destroy, destroy),
    relojes: Object.assign(relojes, relojes),
    marcacionesLocal: Object.assign(marcacionesLocal, marcacionesLocal),
}

export default localesIe