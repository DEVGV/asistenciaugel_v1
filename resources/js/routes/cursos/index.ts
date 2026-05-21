import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:26
* @route '/cursos/{curso}'
*/
export const update = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/cursos/{curso}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:26
* @route '/cursos/{curso}'
*/
update.url = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { curso: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { curso: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            curso: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        curso: typeof args.curso === 'object'
        ? args.curso.id
        : args.curso,
    }

    return update.definition.url
            .replace('{curso}', parsedArgs.curso.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:26
* @route '/cursos/{curso}'
*/
update.put = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:26
* @route '/cursos/{curso}'
*/
update.patch = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:26
* @route '/cursos/{curso}'
*/
const updateForm = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:26
* @route '/cursos/{curso}'
*/
updateForm.put = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:26
* @route '/cursos/{curso}'
*/
updateForm.patch = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:34
* @route '/cursos/{curso}'
*/
export const destroy = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/cursos/{curso}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:34
* @route '/cursos/{curso}'
*/
destroy.url = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { curso: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { curso: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            curso: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        curso: typeof args.curso === 'object'
        ? args.curso.id
        : args.curso,
    }

    return destroy.definition.url
            .replace('{curso}', parsedArgs.curso.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:34
* @route '/cursos/{curso}'
*/
destroy.delete = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:34
* @route '/cursos/{curso}'
*/
const destroyForm = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\CursoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/CursoIEController.php:34
* @route '/cursos/{curso}'
*/
destroyForm.delete = (args: { curso: string | number | { id: string | number } } | [curso: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const cursos = {
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default cursos