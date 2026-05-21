import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Persona\PersonaController::index
* @see app/Http/Controllers/Persona/PersonaController.php:22
* @route '/personas'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/personas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Persona\PersonaController::index
* @see app/Http/Controllers/Persona/PersonaController.php:22
* @route '/personas'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\PersonaController::index
* @see app/Http/Controllers/Persona/PersonaController.php:22
* @route '/personas'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::index
* @see app/Http/Controllers/Persona/PersonaController.php:22
* @route '/personas'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::index
* @see app/Http/Controllers/Persona/PersonaController.php:22
* @route '/personas'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::index
* @see app/Http/Controllers/Persona/PersonaController.php:22
* @route '/personas'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::index
* @see app/Http/Controllers/Persona/PersonaController.php:22
* @route '/personas'
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
* @see \App\Http\Controllers\Persona\PersonaController::store
* @see app/Http/Controllers/Persona/PersonaController.php:46
* @route '/personas'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/personas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Persona\PersonaController::store
* @see app/Http/Controllers/Persona/PersonaController.php:46
* @route '/personas'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\PersonaController::store
* @see app/Http/Controllers/Persona/PersonaController.php:46
* @route '/personas'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::store
* @see app/Http/Controllers/Persona/PersonaController.php:46
* @route '/personas'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::store
* @see app/Http/Controllers/Persona/PersonaController.php:46
* @route '/personas'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Persona\PersonaController::show
* @see app/Http/Controllers/Persona/PersonaController.php:37
* @route '/personas/{persona}'
*/
export const show = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/personas/{persona}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Persona\PersonaController::show
* @see app/Http/Controllers/Persona/PersonaController.php:37
* @route '/personas/{persona}'
*/
show.url = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { persona: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { persona: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            persona: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        persona: typeof args.persona === 'object'
        ? args.persona.id
        : args.persona,
    }

    return show.definition.url
            .replace('{persona}', parsedArgs.persona.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\PersonaController::show
* @see app/Http/Controllers/Persona/PersonaController.php:37
* @route '/personas/{persona}'
*/
show.get = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::show
* @see app/Http/Controllers/Persona/PersonaController.php:37
* @route '/personas/{persona}'
*/
show.head = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::show
* @see app/Http/Controllers/Persona/PersonaController.php:37
* @route '/personas/{persona}'
*/
const showForm = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::show
* @see app/Http/Controllers/Persona/PersonaController.php:37
* @route '/personas/{persona}'
*/
showForm.get = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::show
* @see app/Http/Controllers/Persona/PersonaController.php:37
* @route '/personas/{persona}'
*/
showForm.head = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Persona\PersonaController::update
* @see app/Http/Controllers/Persona/PersonaController.php:54
* @route '/personas/{persona}'
*/
export const update = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/personas/{persona}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Persona\PersonaController::update
* @see app/Http/Controllers/Persona/PersonaController.php:54
* @route '/personas/{persona}'
*/
update.url = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { persona: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { persona: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            persona: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        persona: typeof args.persona === 'object'
        ? args.persona.id
        : args.persona,
    }

    return update.definition.url
            .replace('{persona}', parsedArgs.persona.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\PersonaController::update
* @see app/Http/Controllers/Persona/PersonaController.php:54
* @route '/personas/{persona}'
*/
update.put = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::update
* @see app/Http/Controllers/Persona/PersonaController.php:54
* @route '/personas/{persona}'
*/
update.patch = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::update
* @see app/Http/Controllers/Persona/PersonaController.php:54
* @route '/personas/{persona}'
*/
const updateForm = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::update
* @see app/Http/Controllers/Persona/PersonaController.php:54
* @route '/personas/{persona}'
*/
updateForm.put = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::update
* @see app/Http/Controllers/Persona/PersonaController.php:54
* @route '/personas/{persona}'
*/
updateForm.patch = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Persona\PersonaController::destroy
* @see app/Http/Controllers/Persona/PersonaController.php:62
* @route '/personas/{persona}'
*/
export const destroy = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/personas/{persona}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Persona\PersonaController::destroy
* @see app/Http/Controllers/Persona/PersonaController.php:62
* @route '/personas/{persona}'
*/
destroy.url = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { persona: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { persona: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            persona: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        persona: typeof args.persona === 'object'
        ? args.persona.id
        : args.persona,
    }

    return destroy.definition.url
            .replace('{persona}', parsedArgs.persona.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\PersonaController::destroy
* @see app/Http/Controllers/Persona/PersonaController.php:62
* @route '/personas/{persona}'
*/
destroy.delete = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::destroy
* @see app/Http/Controllers/Persona/PersonaController.php:62
* @route '/personas/{persona}'
*/
const destroyForm = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::destroy
* @see app/Http/Controllers/Persona/PersonaController.php:62
* @route '/personas/{persona}'
*/
destroyForm.delete = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/api/personas/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
searchForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

search.form = searchForm

const PersonaController = { index, store, show, update, destroy, search }

export default PersonaController