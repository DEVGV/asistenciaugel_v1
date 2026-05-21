import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:23
* @route '/trabajadores'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/trabajadores',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:23
* @route '/trabajadores'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:23
* @route '/trabajadores'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:23
* @route '/trabajadores'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:23
* @route '/trabajadores'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:23
* @route '/trabajadores'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:23
* @route '/trabajadores'
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
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:31
* @route '/trabajadores'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/trabajadores',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:31
* @route '/trabajadores'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:31
* @route '/trabajadores'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:31
* @route '/trabajadores'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:31
* @route '/trabajadores'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:39
* @route '/trabajadores/{trabajador}'
*/
export const show = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:39
* @route '/trabajadores/{trabajador}'
*/
show.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { trabajador: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { trabajador: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
    }

    return show.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:39
* @route '/trabajadores/{trabajador}'
*/
show.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:39
* @route '/trabajadores/{trabajador}'
*/
show.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:39
* @route '/trabajadores/{trabajador}'
*/
const showForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:39
* @route '/trabajadores/{trabajador}'
*/
showForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:39
* @route '/trabajadores/{trabajador}'
*/
showForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::edit
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:46
* @route '/trabajadores/{trabajador}/edit'
*/
export const edit = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::edit
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:46
* @route '/trabajadores/{trabajador}/edit'
*/
edit.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { trabajador: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { trabajador: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
    }

    return edit.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::edit
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:46
* @route '/trabajadores/{trabajador}/edit'
*/
edit.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::edit
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:46
* @route '/trabajadores/{trabajador}/edit'
*/
edit.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::edit
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:46
* @route '/trabajadores/{trabajador}/edit'
*/
const editForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::edit
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:46
* @route '/trabajadores/{trabajador}/edit'
*/
editForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::edit
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:46
* @route '/trabajadores/{trabajador}/edit'
*/
editForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:53
* @route '/trabajadores/{trabajador}'
*/
export const update = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/trabajadores/{trabajador}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:53
* @route '/trabajadores/{trabajador}'
*/
update.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { trabajador: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { trabajador: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
    }

    return update.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:53
* @route '/trabajadores/{trabajador}'
*/
update.put = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:53
* @route '/trabajadores/{trabajador}'
*/
update.patch = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:53
* @route '/trabajadores/{trabajador}'
*/
const updateForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:53
* @route '/trabajadores/{trabajador}'
*/
updateForm.put = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:53
* @route '/trabajadores/{trabajador}'
*/
updateForm.patch = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:61
* @route '/trabajadores/{trabajador}'
*/
export const destroy = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/trabajadores/{trabajador}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:61
* @route '/trabajadores/{trabajador}'
*/
destroy.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { trabajador: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { trabajador: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
    }

    return destroy.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:61
* @route '/trabajadores/{trabajador}'
*/
destroy.delete = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:61
* @route '/trabajadores/{trabajador}'
*/
const destroyForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:61
* @route '/trabajadores/{trabajador}'
*/
destroyForm.delete = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const trabajadores = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default trabajadores