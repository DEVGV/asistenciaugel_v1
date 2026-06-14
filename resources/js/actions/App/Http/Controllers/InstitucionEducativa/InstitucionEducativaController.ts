import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::index
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:26
* @route '/instituciones'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/instituciones',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::index
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:26
* @route '/instituciones'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::index
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:26
* @route '/instituciones'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::index
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:26
* @route '/instituciones'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::index
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:26
* @route '/instituciones'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::index
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:26
* @route '/instituciones'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::index
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:26
* @route '/instituciones'
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
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:39
* @route '/instituciones'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:39
* @route '/instituciones'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:39
* @route '/instituciones'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:39
* @route '/instituciones'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::store
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:39
* @route '/instituciones'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::show
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:47
* @route '/instituciones/{institucione}'
*/
export const show = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::show
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:47
* @route '/instituciones/{institucione}'
*/
show.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::show
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:47
* @route '/instituciones/{institucione}'
*/
show.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::show
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:47
* @route '/instituciones/{institucione}'
*/
show.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::show
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:47
* @route '/instituciones/{institucione}'
*/
const showForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::show
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:47
* @route '/instituciones/{institucione}'
*/
showForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::show
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:47
* @route '/instituciones/{institucione}'
*/
showForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:78
* @route '/instituciones/{institucione}'
*/
export const update = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/instituciones/{institucione}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:78
* @route '/instituciones/{institucione}'
*/
update.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:78
* @route '/instituciones/{institucione}'
*/
update.put = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:78
* @route '/instituciones/{institucione}'
*/
update.patch = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:78
* @route '/instituciones/{institucione}'
*/
const updateForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:78
* @route '/instituciones/{institucione}'
*/
updateForm.put = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:78
* @route '/instituciones/{institucione}'
*/
updateForm.patch = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::destroy
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:86
* @route '/instituciones/{institucione}'
*/
export const destroy = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/instituciones/{institucione}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::destroy
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:86
* @route '/instituciones/{institucione}'
*/
destroy.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::destroy
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:86
* @route '/instituciones/{institucione}'
*/
destroy.delete = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::destroy
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:86
* @route '/instituciones/{institucione}'
*/
const destroyForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::destroy
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:86
* @route '/instituciones/{institucione}'
*/
destroyForm.delete = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/docentes'
*/
export const docentes = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: docentes.url(args, options),
    method: 'get',
})

docentes.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/docentes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/docentes'
*/
docentes.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return docentes.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/docentes'
*/
docentes.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: docentes.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/docentes'
*/
docentes.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: docentes.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/docentes'
*/
const docentesForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: docentes.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/docentes'
*/
docentesForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: docentes.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/docentes'
*/
docentesForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: docentes.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

docentes.form = docentesForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/api/instituciones/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
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

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detallesJson
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
export const detallesJson = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detallesJson.url(args, options),
    method: 'get',
})

detallesJson.definition = {
    methods: ["get","head"],
    url: '/api/instituciones/{institucione}/detalles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detallesJson
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detallesJson.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return detallesJson.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detallesJson
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detallesJson.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detallesJson.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detallesJson
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detallesJson.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detallesJson.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detallesJson
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
const detallesJsonForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detallesJson.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detallesJson
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detallesJsonForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detallesJson.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detallesJson
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detallesJsonForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detallesJson.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

detallesJson.form = detallesJsonForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
export const locales = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: locales.url(args, options),
    method: 'get',
})

locales.definition = {
    methods: ["get","head"],
    url: '/api/instituciones/{institucione}/locales',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
locales.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return locales.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
locales.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: locales.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
locales.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: locales.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
const localesForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: locales.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
localesForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: locales.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
localesForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: locales.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

locales.form = localesForm

const InstitucionEducativaController = { index, store, show, update, destroy, docentes, search, detallesJson, locales }

export default InstitucionEducativaController