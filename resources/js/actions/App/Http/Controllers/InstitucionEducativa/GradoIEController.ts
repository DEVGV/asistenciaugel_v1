import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:18
* @route '/instituciones/{institucione}/grados'
*/
export const store = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/grados',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:18
* @route '/instituciones/{institucione}/grados'
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
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:18
* @route '/instituciones/{institucione}/grados'
*/
store.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:18
* @route '/instituciones/{institucione}/grados'
*/
const storeForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:18
* @route '/instituciones/{institucione}/grados'
*/
storeForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:26
* @route '/grados/{grado}'
*/
export const update = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/grados/{grado}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:26
* @route '/grados/{grado}'
*/
update.url = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { grado: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { grado: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            grado: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        grado: typeof args.grado === 'object'
        ? args.grado.id
        : args.grado,
    }

    return update.definition.url
            .replace('{grado}', parsedArgs.grado.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:26
* @route '/grados/{grado}'
*/
update.put = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:26
* @route '/grados/{grado}'
*/
update.patch = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:26
* @route '/grados/{grado}'
*/
const updateForm = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:26
* @route '/grados/{grado}'
*/
updateForm.put = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:26
* @route '/grados/{grado}'
*/
updateForm.patch = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:34
* @route '/grados/{grado}'
*/
export const destroy = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/grados/{grado}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:34
* @route '/grados/{grado}'
*/
destroy.url = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { grado: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { grado: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            grado: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        grado: typeof args.grado === 'object'
        ? args.grado.id
        : args.grado,
    }

    return destroy.definition.url
            .replace('{grado}', parsedArgs.grado.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:34
* @route '/grados/{grado}'
*/
destroy.delete = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:34
* @route '/grados/{grado}'
*/
const destroyForm = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\GradoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/GradoIEController.php:34
* @route '/grados/{grado}'
*/
destroyForm.delete = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const GradoIEController = { store, update, destroy }

export default GradoIEController