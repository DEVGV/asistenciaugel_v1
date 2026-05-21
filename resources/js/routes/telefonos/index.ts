import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:26
* @route '/telefonos/{telefono}'
*/
export const update = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/telefonos/{telefono}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:26
* @route '/telefonos/{telefono}'
*/
update.url = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { telefono: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { telefono: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            telefono: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        telefono: typeof args.telefono === 'object'
        ? args.telefono.id
        : args.telefono,
    }

    return update.definition.url
            .replace('{telefono}', parsedArgs.telefono.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:26
* @route '/telefonos/{telefono}'
*/
update.put = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:26
* @route '/telefonos/{telefono}'
*/
update.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:26
* @route '/telefonos/{telefono}'
*/
const updateForm = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:26
* @route '/telefonos/{telefono}'
*/
updateForm.put = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:26
* @route '/telefonos/{telefono}'
*/
updateForm.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
*/
export const destroy = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/telefonos/{telefono}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
*/
destroy.url = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { telefono: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { telefono: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            telefono: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        telefono: typeof args.telefono === 'object'
        ? args.telefono.id
        : args.telefono,
    }

    return destroy.definition.url
            .replace('{telefono}', parsedArgs.telefono.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
*/
destroy.delete = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
*/
const destroyForm = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
*/
destroyForm.delete = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const telefonos = {
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default telefonos