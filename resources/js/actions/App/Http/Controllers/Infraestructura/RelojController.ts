import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
export const store = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/locales-ie/{localesIe}/relojes',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
store.url = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{localesIe}', parsedArgs.localesIe.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
store.post = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
const storeForm = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
storeForm.post = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::update
* @see app/Http/Controllers/Infraestructura/RelojController.php:29
* @route '/relojes/{reloje}'
*/
export const update = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/relojes/{reloje}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::update
* @see app/Http/Controllers/Infraestructura/RelojController.php:29
* @route '/relojes/{reloje}'
*/
update.url = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { reloje: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { reloje: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            reloje: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        reloje: typeof args.reloje === 'object'
        ? args.reloje.id
        : args.reloje,
    }

    return update.definition.url
            .replace('{reloje}', parsedArgs.reloje.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::update
* @see app/Http/Controllers/Infraestructura/RelojController.php:29
* @route '/relojes/{reloje}'
*/
update.put = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::update
* @see app/Http/Controllers/Infraestructura/RelojController.php:29
* @route '/relojes/{reloje}'
*/
update.patch = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::update
* @see app/Http/Controllers/Infraestructura/RelojController.php:29
* @route '/relojes/{reloje}'
*/
const updateForm = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::update
* @see app/Http/Controllers/Infraestructura/RelojController.php:29
* @route '/relojes/{reloje}'
*/
updateForm.put = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::update
* @see app/Http/Controllers/Infraestructura/RelojController.php:29
* @route '/relojes/{reloje}'
*/
updateForm.patch = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::destroy
* @see app/Http/Controllers/Infraestructura/RelojController.php:37
* @route '/relojes/{reloje}'
*/
export const destroy = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/relojes/{reloje}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::destroy
* @see app/Http/Controllers/Infraestructura/RelojController.php:37
* @route '/relojes/{reloje}'
*/
destroy.url = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { reloje: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { reloje: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            reloje: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        reloje: typeof args.reloje === 'object'
        ? args.reloje.id
        : args.reloje,
    }

    return destroy.definition.url
            .replace('{reloje}', parsedArgs.reloje.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::destroy
* @see app/Http/Controllers/Infraestructura/RelojController.php:37
* @route '/relojes/{reloje}'
*/
destroy.delete = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::destroy
* @see app/Http/Controllers/Infraestructura/RelojController.php:37
* @route '/relojes/{reloje}'
*/
const destroyForm = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::destroy
* @see app/Http/Controllers/Infraestructura/RelojController.php:37
* @route '/relojes/{reloje}'
*/
destroyForm.delete = (args: { reloje: number | { id: number } } | [reloje: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const RelojController = { store, update, destroy }

export default RelojController