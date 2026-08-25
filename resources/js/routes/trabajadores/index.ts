import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import masivo from './masivo'
import altas from './altas'
/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:27
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:27
* @route '/trabajadores'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:27
* @route '/trabajadores'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:27
* @route '/trabajadores'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:27
* @route '/trabajadores'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:27
* @route '/trabajadores'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:27
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
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:62
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:62
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:62
* @route '/trabajadores/{trabajador}'
*/
show.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:62
* @route '/trabajadores/{trabajador}'
*/
show.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:62
* @route '/trabajadores/{trabajador}'
*/
const showForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:62
* @route '/trabajadores/{trabajador}'
*/
showForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:62
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
* @see \App\Http\Controllers\Trabajador\TrabajadorController::showTab
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:71
* @route '/trabajadores/{trabajador}/{tab}'
*/
export const showTab = (args: { trabajador: string | number | { id: string | number }, tab: string | number } | [trabajador: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showTab.url(args, options),
    method: 'get',
})

showTab.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/{tab}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::showTab
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:71
* @route '/trabajadores/{trabajador}/{tab}'
*/
showTab.url = (args: { trabajador: string | number | { id: string | number }, tab: string | number } | [trabajador: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
            tab: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
        tab: args.tab,
    }

    return showTab.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace('{tab}', parsedArgs.tab.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::showTab
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:71
* @route '/trabajadores/{trabajador}/{tab}'
*/
showTab.get = (args: { trabajador: string | number | { id: string | number }, tab: string | number } | [trabajador: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showTab.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::showTab
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:71
* @route '/trabajadores/{trabajador}/{tab}'
*/
showTab.head = (args: { trabajador: string | number | { id: string | number }, tab: string | number } | [trabajador: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showTab.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::showTab
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:71
* @route '/trabajadores/{trabajador}/{tab}'
*/
const showTabForm = (args: { trabajador: string | number | { id: string | number }, tab: string | number } | [trabajador: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: showTab.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::showTab
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:71
* @route '/trabajadores/{trabajador}/{tab}'
*/
showTabForm.get = (args: { trabajador: string | number | { id: string | number }, tab: string | number } | [trabajador: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: showTab.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::showTab
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:71
* @route '/trabajadores/{trabajador}/{tab}'
*/
showTabForm.head = (args: { trabajador: string | number | { id: string | number }, tab: string | number } | [trabajador: string | number | { id: string | number }, tab: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::horarios
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:28
* @route '/trabajadores/{trabajador}/horarios'
*/
export const horarios = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: horarios.url(args, options),
    method: 'get',
})

horarios.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/horarios',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::horarios
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:28
* @route '/trabajadores/{trabajador}/horarios'
*/
horarios.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return horarios.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::horarios
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:28
* @route '/trabajadores/{trabajador}/horarios'
*/
horarios.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: horarios.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::horarios
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:28
* @route '/trabajadores/{trabajador}/horarios'
*/
horarios.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: horarios.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::horarios
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:28
* @route '/trabajadores/{trabajador}/horarios'
*/
const horariosForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: horarios.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::horarios
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:28
* @route '/trabajadores/{trabajador}/horarios'
*/
horariosForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: horarios.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Horario\HorarioTrabajadorController::horarios
* @see app/Http/Controllers/Horario/HorarioTrabajadorController.php:28
* @route '/trabajadores/{trabajador}/horarios'
*/
horariosForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: horarios.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

horarios.form = horariosForm

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::marcaciones
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
export const marcaciones = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marcaciones.url(args, options),
    method: 'get',
})

marcaciones.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/marcaciones',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::marcaciones
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
marcaciones.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return marcaciones.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::marcaciones
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
marcaciones.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: marcaciones.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::marcaciones
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
marcaciones.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: marcaciones.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::marcaciones
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
const marcacionesForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marcaciones.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::marcaciones
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
marcacionesForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marcaciones.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::marcaciones
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:18
* @route '/trabajadores/{trabajador}/marcaciones'
*/
marcacionesForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: marcaciones.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

marcaciones.form = marcacionesForm

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
export const asistenciaConsolidada = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: asistenciaConsolidada.url(args, options),
    method: 'get',
})

asistenciaConsolidada.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}/asistencia-consolidada',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidada.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return asistenciaConsolidada.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidada.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: asistenciaConsolidada.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidada.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: asistenciaConsolidada.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
const asistenciaConsolidadaForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: asistenciaConsolidada.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidadaForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: asistenciaConsolidada.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\MarcacionesTrabajadorController::asistenciaConsolidada
* @see app/Http/Controllers/Trabajador/MarcacionesTrabajadorController.php:47
* @route '/trabajadores/{trabajador}/asistencia-consolidada'
*/
asistenciaConsolidadaForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: asistenciaConsolidada.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

asistenciaConsolidada.form = asistenciaConsolidadaForm

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:54
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:54
* @route '/trabajadores'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:54
* @route '/trabajadores'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:54
* @route '/trabajadores'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:54
* @route '/trabajadores'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:96
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:96
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:96
* @route '/trabajadores/{trabajador}'
*/
update.put = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:96
* @route '/trabajadores/{trabajador}'
*/
update.patch = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::update
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:96
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:96
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:96
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:104
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:104
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:104
* @route '/trabajadores/{trabajador}'
*/
destroy.delete = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:104
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
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:104
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
    show: Object.assign(show, show),
    showTab: Object.assign(showTab, showTab),
    horarios: Object.assign(horarios, horarios),
    marcaciones: Object.assign(marcaciones, marcaciones),
    asistenciaConsolidada: Object.assign(asistenciaConsolidada, asistenciaConsolidada),
    store: Object.assign(store, store),
    masivo: Object.assign(masivo, masivo),
    altas: Object.assign(altas, altas),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default trabajadores