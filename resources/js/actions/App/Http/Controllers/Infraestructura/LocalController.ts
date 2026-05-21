import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Infraestructura\LocalController::index
* @see app/Http/Controllers/Infraestructura/LocalController.php:21
* @route '/locales'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/locales',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::index
* @see app/Http/Controllers/Infraestructura/LocalController.php:21
* @route '/locales'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::index
* @see app/Http/Controllers/Infraestructura/LocalController.php:21
* @route '/locales'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::index
* @see app/Http/Controllers/Infraestructura/LocalController.php:21
* @route '/locales'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::index
* @see app/Http/Controllers/Infraestructura/LocalController.php:21
* @route '/locales'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::index
* @see app/Http/Controllers/Infraestructura/LocalController.php:21
* @route '/locales'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::index
* @see app/Http/Controllers/Infraestructura/LocalController.php:21
* @route '/locales'
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
* @see \App\Http\Controllers\Infraestructura\LocalController::store
* @see app/Http/Controllers/Infraestructura/LocalController.php:29
* @route '/locales'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/locales',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::store
* @see app/Http/Controllers/Infraestructura/LocalController.php:29
* @route '/locales'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::store
* @see app/Http/Controllers/Infraestructura/LocalController.php:29
* @route '/locales'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::store
* @see app/Http/Controllers/Infraestructura/LocalController.php:29
* @route '/locales'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::store
* @see app/Http/Controllers/Infraestructura/LocalController.php:29
* @route '/locales'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::update
* @see app/Http/Controllers/Infraestructura/LocalController.php:36
* @route '/locales/{local}'
*/
export const update = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/locales/{local}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::update
* @see app/Http/Controllers/Infraestructura/LocalController.php:36
* @route '/locales/{local}'
*/
update.url = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { local: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { local: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            local: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        local: typeof args.local === 'object'
        ? args.local.id
        : args.local,
    }

    return update.definition.url
            .replace('{local}', parsedArgs.local.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::update
* @see app/Http/Controllers/Infraestructura/LocalController.php:36
* @route '/locales/{local}'
*/
update.put = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::update
* @see app/Http/Controllers/Infraestructura/LocalController.php:36
* @route '/locales/{local}'
*/
update.patch = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::update
* @see app/Http/Controllers/Infraestructura/LocalController.php:36
* @route '/locales/{local}'
*/
const updateForm = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::update
* @see app/Http/Controllers/Infraestructura/LocalController.php:36
* @route '/locales/{local}'
*/
updateForm.put = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::update
* @see app/Http/Controllers/Infraestructura/LocalController.php:36
* @route '/locales/{local}'
*/
updateForm.patch = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Infraestructura\LocalController::destroy
* @see app/Http/Controllers/Infraestructura/LocalController.php:43
* @route '/locales/{local}'
*/
export const destroy = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/locales/{local}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::destroy
* @see app/Http/Controllers/Infraestructura/LocalController.php:43
* @route '/locales/{local}'
*/
destroy.url = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { local: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { local: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            local: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        local: typeof args.local === 'object'
        ? args.local.id
        : args.local,
    }

    return destroy.definition.url
            .replace('{local}', parsedArgs.local.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::destroy
* @see app/Http/Controllers/Infraestructura/LocalController.php:43
* @route '/locales/{local}'
*/
destroy.delete = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::destroy
* @see app/Http/Controllers/Infraestructura/LocalController.php:43
* @route '/locales/{local}'
*/
const destroyForm = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalController::destroy
* @see app/Http/Controllers/Infraestructura/LocalController.php:43
* @route '/locales/{local}'
*/
destroyForm.delete = (args: { local: number | { id: number } } | [local: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const LocalController = { index, store, update, destroy }

export default LocalController