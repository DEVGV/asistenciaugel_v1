import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::update
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:26
* @route '/secciones/{seccione}'
*/
export const update = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/secciones/{seccione}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::update
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:26
* @route '/secciones/{seccione}'
*/
update.url = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { seccione: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { seccione: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            seccione: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        seccione: typeof args.seccione === 'object'
        ? args.seccione.id
        : args.seccione,
    }

    return update.definition.url
            .replace('{seccione}', parsedArgs.seccione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::update
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:26
* @route '/secciones/{seccione}'
*/
update.put = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::update
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:26
* @route '/secciones/{seccione}'
*/
update.patch = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::update
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:26
* @route '/secciones/{seccione}'
*/
const updateForm = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::update
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:26
* @route '/secciones/{seccione}'
*/
updateForm.put = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::update
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:26
* @route '/secciones/{seccione}'
*/
updateForm.patch = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:35
* @route '/secciones/{seccione}'
*/
export const destroy = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/secciones/{seccione}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:35
* @route '/secciones/{seccione}'
*/
destroy.url = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { seccione: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { seccione: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            seccione: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        seccione: typeof args.seccione === 'object'
        ? args.seccione.id
        : args.seccione,
    }

    return destroy.definition.url
            .replace('{seccione}', parsedArgs.seccione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:35
* @route '/secciones/{seccione}'
*/
destroy.delete = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:35
* @route '/secciones/{seccione}'
*/
const destroyForm = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:35
* @route '/secciones/{seccione}'
*/
destroyForm.delete = (args: { seccione: string | number | { id: string | number } } | [seccione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const secciones = {
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default secciones