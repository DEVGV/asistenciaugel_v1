import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import consolidadoAsistencia from './consolidado-asistencia'
import masivo from './masivo'
import altasMasivas from './altas-masivas'
import gradosMasivos from './grados-masivos'
import cursosMasivos from './cursos-masivos'
import telefonosIe from './telefonos-ie'
import emailsIe from './emails-ie'
import domiciliosIe from './domicilios-ie'
import diasNoLaborables from './dias-no-laborables'
import cursos from './cursos'
import grados from './grados'
import localesIe from './locales-ie'
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
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::showTab
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/{tab}'
*/
export const showTab = (args: { institucione: string | number | { id: string | number }, tab: string | number } | [institucione: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showTab.url(args, options),
    method: 'get',
})

showTab.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/{tab}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::showTab
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/{tab}'
*/
showTab.url = (args: { institucione: string | number | { id: string | number }, tab: string | number } | [institucione: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            institucione: args[0],
            tab: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        institucione: typeof args.institucione === 'object'
        ? args.institucione.id
        : args.institucione,
        tab: args.tab,
    }

    return showTab.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace('{tab}', parsedArgs.tab.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::showTab
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/{tab}'
*/
showTab.get = (args: { institucione: string | number | { id: string | number }, tab: string | number } | [institucione: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showTab.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::showTab
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/{tab}'
*/
showTab.head = (args: { institucione: string | number | { id: string | number }, tab: string | number } | [institucione: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showTab.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::showTab
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/{tab}'
*/
const showTabForm = (args: { institucione: string | number | { id: string | number }, tab: string | number } | [institucione: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: showTab.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::showTab
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/{tab}'
*/
showTabForm.get = (args: { institucione: string | number | { id: string | number }, tab: string | number } | [institucione: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: showTab.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::showTab
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:54
* @route '/instituciones/{institucione}/{tab}'
*/
showTabForm.head = (args: { institucione: string | number | { id: string | number }, tab: string | number } | [institucione: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: showTab.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

showTab.form = showTabForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:68
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:68
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:68
* @route '/instituciones/{institucione}/docentes'
*/
docentes.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: docentes.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:68
* @route '/instituciones/{institucione}/docentes'
*/
docentes.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: docentes.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:68
* @route '/instituciones/{institucione}/docentes'
*/
const docentesForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: docentes.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:68
* @route '/instituciones/{institucione}/docentes'
*/
docentesForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: docentes.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::docentes
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:68
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
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:92
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:92
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:92
* @route '/instituciones/{institucione}'
*/
update.put = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:92
* @route '/instituciones/{institucione}'
*/
update.patch = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::update
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:92
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:92
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:92
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:100
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:100
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:100
* @route '/instituciones/{institucione}'
*/
destroy.delete = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::destroy
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:100
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
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:100
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

const instituciones = {
    index: Object.assign(index, index),
    show: Object.assign(show, show),
    showTab: Object.assign(showTab, showTab),
    docentes: Object.assign(docentes, docentes),
    consolidadoAsistencia: Object.assign(consolidadoAsistencia, consolidadoAsistencia),
    store: Object.assign(store, store),
    masivo: Object.assign(masivo, masivo),
    update: Object.assign(update, update),
    altasMasivas: Object.assign(altasMasivas, altasMasivas),
    gradosMasivos: Object.assign(gradosMasivos, gradosMasivos),
    cursosMasivos: Object.assign(cursosMasivos, cursosMasivos),
    telefonosIe: Object.assign(telefonosIe, telefonosIe),
    emailsIe: Object.assign(emailsIe, emailsIe),
    domiciliosIe: Object.assign(domiciliosIe, domiciliosIe),
    diasNoLaborables: Object.assign(diasNoLaborables, diasNoLaborables),
    cursos: Object.assign(cursos, cursos),
    grados: Object.assign(grados, grados),
    destroy: Object.assign(destroy, destroy),
    localesIe: Object.assign(localesIe, localesIe),
}

export default instituciones