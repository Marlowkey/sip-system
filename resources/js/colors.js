export const gradientBgBase = 'bg-gradient-to-tr'
export const gradientBgPurplePink = `${gradientBgBase} from-purple-400 via-pink-500 to-red-500`
export const gradientBgDark = `${gradientBgBase} from-slate-700 via-slate-900 to-slate-800`
export const gradientBgBlueIndigo = `${gradientBgBase} from-[#020024] via-[#2222ab] to-[#00d4ff] `

export const colorsBgLight = {
  white: 'bg-white text-black',
  light: 'bg-white text-black dark:bg-slate-900/70 dark:text-white',
  contrast: 'bg-gray-800 text-white dark:bg-white dark:text-black',
  success: 'bg-emerald-500 border-emerald-500 text-white',
  danger: 'bg-red-500 border-red-500 text-white',
  warning: 'bg-yellow-500 border-yellow-500 text-white',
  info: 'bg-blue-500 border-blue-500 text-white'
}

export const colorsText = {
  white: 'text-black dark:text-slate-100',
  light: 'text-gray-700 dark:text-slate-400',
  contrast: 'dark:text-white',
  success: 'text-emerald-500',
  danger: 'text-red-500',
  warning: 'text-yellow-500',
  info: 'text-blue-500'
}

export const colorsOutline = {
  white: [colorsText.white, 'border-gray-100'],
  light: [colorsText.light, 'border-gray-100'],
  contrast: [colorsText.contrast, 'border-gray-900 dark:border-slate-100'],
  success: [colorsText.success, 'border-emerald-500'],
  danger: [colorsText.danger, 'border-red-500'],
  warning: [colorsText.warning, 'border-yellow-500'],
  info: [colorsText.info, 'border-blue-500']
}

export const getButtonColor = (color, isOutlined, hasHover, isActive = false) => {
  const colors = {
    ring: {
      white: 'ring-gray-200 dark:ring-gray-500',
      whiteDark: 'ring-gray-200 dark:ring-gray-500',
      lightDark: 'ring-gray-200 dark:ring-gray-500',
      contrast: 'ring-gray-300 dark:ring-gray-400',
      success: 'ring-emerald-300 dark:ring-emerald-700',
      danger: 'ring-red-300 dark:ring-red-700',
      warning: 'ring-yellow-300 dark:ring-yellow-700',
      info: 'ring-blue-300 dark:ring-blue-700'
    },
    active: {
      white: 'bg-gray-100',
      whiteDark: 'bg-gray-100 dark:bg-slate-800',
      lightDark: 'bg-gray-200 dark:bg-slate-700',
      contrast: 'bg-gray-700 dark:bg-slate-100',
      success: 'bg-emerald-700 dark:bg-emerald-600',
      danger: 'bg-red-700 dark:bg-red-600',
      warning: 'bg-yellow-700 dark:bg-yellow-600',
      info: 'bg-blue-700 dark:bg-blue-600'
    },
    bg: {
      white: 'bg-white text-black',
      whiteDark: 'bg-white text-black dark:bg-slate-900 dark:text-white',
      lightDark: 'bg-gray-100 text-black dark:bg-slate-800 dark:text-white',
      contrast: 'bg-gray-800 text-white dark:bg-white dark:text-black',
      success: 'bg-emerald-600 dark:bg-emerald-500 text-white',
      danger: 'bg-red-600 dark:bg-red-500 text-white',
      warning: 'bg-yellow-600 dark:bg-yellow-500 text-white',
      info: 'bg-blue-600 dark:bg-blue-500 text-white',
      blue:'border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-blue-900 dark:text-blue-400',
      teal: 'border border-transparent bg-teal-100 text-teal-800 hover:bg-teal-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-teal-900 dark:text-teal-500 dark:hover:text-teal-400',
      whiteTwo: 'bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800',
      yellow: 'border border-transparent bg-yellow-100 text-yellow-800 hover:bg-yellow-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-yellow-900 dark:text-yellow-500 dark:hover:text-yellow-400rounded-lg border border-transparent bg-yellow-100 text-yellow-800 hover:bg-yellow-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-yellow-900 dark:text-yellow-500 dark:hover:text-yellow-400',
      red: 'border border-transparent bg-red-100 text-red-800 hover:bg-red-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-red-900 dark:text-red-500 dark:hover:text-red-400  bg-red-100 text-red-800 hover:bg-red-200 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-red-900 dark:text-red-500 dark:hover:text-red-400',
      dark: 'text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700',
     light: 'text-gray-900 bg-white border-b-2 border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700',
    borderBlue:'font-semibold rounded-full border border-b-2 focus:outline-none focus:ring transition text-white bg-blue-500 border-blue-800 hover:bg-blue-600 active:bg-blue-700 focus:ring-blue-300'
    },
    bgHover: {
      white: 'hover:bg-gray-100',
      whiteDark: 'hover:bg-gray-100 hover:dark:bg-slate-800',
      lightDark: 'hover:bg-gray-200 hover:dark:bg-slate-700',
      contrast: 'hover:bg-gray-700 hover:dark:bg-slate-100',
      success:
        'hover:bg-emerald-700 hover:border-emerald-700 hover:dark:bg-emerald-600 hover:dark:border-emerald-600',
      danger:
        'hover:bg-red-700 hover:border-red-700 hover:dark:bg-red-600 hover:dark:border-red-600',
      warning:
        'hover:bg-yellow-700 hover:border-yellow-700 hover:dark:bg-yellow-600 hover:dark:border-yellow-600',
      info: 'hover:bg-blue-700 hover:border-blue-700 hover:dark:bg-blue-600 hover:dark:border-blue-600'
    },
    borders: {
      white: 'border-white',
      whiteDark: 'border-white dark:border-slate-900',
      lightDark: 'border-gray-100 dark:border-slate-800',
      contrast: 'border-gray-800 dark:border-white',
      success: 'border-emerald-600 dark:border-emerald-500',
      danger: 'border-red-600 dark:border-red-500',
      warning: 'border-yellow-600 dark:border-yellow-500',
      info: 'border-blue-600 dark:border-blue-500'
    },
    text: {
      contrast: 'dark:text-slate-100',
      success: 'text-emerald-600 dark:text-emerald-500',
      danger: 'text-red-600 dark:text-red-500',
      warning: 'text-yellow-600 dark:text-yellow-500',
      info: 'text-blue-600 dark:text-blue-500'
    },
    outlineHover: {
      contrast:
        'hover:bg-gray-800 hover:text-gray-100 hover:dark:bg-slate-100 hover:dark:text-black',
      success:
        'hover:bg-emerald-600 hover:text-white hover:text-white hover:dark:text-white hover:dark:border-emerald-600',
      danger:
        'hover:bg-red-600 hover:text-white hover:text-white hover:dark:text-white hover:dark:border-red-600',
      warning:
        'hover:bg-yellow-600 hover:text-white hover:text-white hover:dark:text-white hover:dark:border-yellow-600',
      info: 'hover:bg-blue-600 hover:text-white hover:dark:text-white hover:dark:border-blue-600'
    }
  }

  if (!colors.bg[color]) {
    return color
  }

  const isOutlinedProcessed = isOutlined && ['white', 'whiteDark', 'lightDark'].indexOf(color) < 0

  const base = [colors.borders[color], colors.ring[color]]

  if (isActive) {
    base.push(colors.active[color])
  } else {
    base.push(isOutlinedProcessed ? colors.text[color] : colors.bg[color])
  }

  if (hasHover) {
    base.push(isOutlinedProcessed ? colors.outlineHover[color] : colors.bgHover[color])
  }

  return base
}
