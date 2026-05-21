import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import secciones from './secciones'
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

const grados = {
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
    secciones: Object.assign(secciones, secciones),
}

export default grados