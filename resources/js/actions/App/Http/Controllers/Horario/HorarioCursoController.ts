import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::index
* @see app/Http/Controllers/Horario/HorarioCursoController.php:19
* @route '/horarios-cursos'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/horarios-cursos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::index
* @see app/Http/Controllers/Horario/HorarioCursoController.php:19
* @route '/horarios-cursos'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::index
* @see app/Http/Controllers/Horario/HorarioCursoController.php:19
* @route '/horarios-cursos'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::index
* @see app/Http/Controllers/Horario/HorarioCursoController.php:19
* @route '/horarios-cursos'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::index
* @see app/Http/Controllers/Horario/HorarioCursoController.php:19
* @route '/horarios-cursos'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::index
* @see app/Http/Controllers/Horario/HorarioCursoController.php:19
* @route '/horarios-cursos'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::index
* @see app/Http/Controllers/Horario/HorarioCursoController.php:19
* @route '/horarios-cursos'
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
* @see \App\Http\Controllers\Horario\HorarioCursoController::store
* @see app/Http/Controllers/Horario/HorarioCursoController.php:31
* @route '/horarios-cursos'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/horarios-cursos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::store
* @see app/Http/Controllers/Horario/HorarioCursoController.php:31
* @route '/horarios-cursos'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::store
* @see app/Http/Controllers/Horario/HorarioCursoController.php:31
* @route '/horarios-cursos'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::store
* @see app/Http/Controllers/Horario/HorarioCursoController.php:31
* @route '/horarios-cursos'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::store
* @see app/Http/Controllers/Horario/HorarioCursoController.php:31
* @route '/horarios-cursos'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::update
* @see app/Http/Controllers/Horario/HorarioCursoController.php:41
* @route '/horarios-cursos/{horarioCurso}'
*/
export const update = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/horarios-cursos/{horarioCurso}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::update
* @see app/Http/Controllers/Horario/HorarioCursoController.php:41
* @route '/horarios-cursos/{horarioCurso}'
*/
update.url = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { horarioCurso: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { horarioCurso: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            horarioCurso: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        horarioCurso: typeof args.horarioCurso === 'object'
        ? args.horarioCurso.id
        : args.horarioCurso,
    }

    return update.definition.url
            .replace('{horarioCurso}', parsedArgs.horarioCurso.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::update
* @see app/Http/Controllers/Horario/HorarioCursoController.php:41
* @route '/horarios-cursos/{horarioCurso}'
*/
update.put = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::update
* @see app/Http/Controllers/Horario/HorarioCursoController.php:41
* @route '/horarios-cursos/{horarioCurso}'
*/
update.patch = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::update
* @see app/Http/Controllers/Horario/HorarioCursoController.php:41
* @route '/horarios-cursos/{horarioCurso}'
*/
const updateForm = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::update
* @see app/Http/Controllers/Horario/HorarioCursoController.php:41
* @route '/horarios-cursos/{horarioCurso}'
*/
updateForm.put = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::update
* @see app/Http/Controllers/Horario/HorarioCursoController.php:41
* @route '/horarios-cursos/{horarioCurso}'
*/
updateForm.patch = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Horario\HorarioCursoController::destroy
* @see app/Http/Controllers/Horario/HorarioCursoController.php:51
* @route '/horarios-cursos/{horarioCurso}'
*/
export const destroy = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/horarios-cursos/{horarioCurso}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::destroy
* @see app/Http/Controllers/Horario/HorarioCursoController.php:51
* @route '/horarios-cursos/{horarioCurso}'
*/
destroy.url = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { horarioCurso: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { horarioCurso: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            horarioCurso: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        horarioCurso: typeof args.horarioCurso === 'object'
        ? args.horarioCurso.id
        : args.horarioCurso,
    }

    return destroy.definition.url
            .replace('{horarioCurso}', parsedArgs.horarioCurso.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::destroy
* @see app/Http/Controllers/Horario/HorarioCursoController.php:51
* @route '/horarios-cursos/{horarioCurso}'
*/
destroy.delete = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::destroy
* @see app/Http/Controllers/Horario/HorarioCursoController.php:51
* @route '/horarios-cursos/{horarioCurso}'
*/
const destroyForm = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioCursoController::destroy
* @see app/Http/Controllers/Horario/HorarioCursoController.php:51
* @route '/horarios-cursos/{horarioCurso}'
*/
destroyForm.delete = (args: { horarioCurso: number | { id: number } } | [horarioCurso: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const HorarioCursoController = { index, store, update, destroy }

export default HorarioCursoController