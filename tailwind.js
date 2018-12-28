/*

Tailwind - The Utility-First CSS Framework

A project by Adam Wathan (@adamwathan), Jonathan Reinink (@reinink),
David Hemphill (@davidhemphill) and Steve Schoger (@steveschoger).

Welcome to the Tailwind config file. This is where you can customize
Tailwind specifically for your project. Don't be intimidated by the
length of this file. It's really just a big JavaScript object and
we've done our very best to explain each section.

View the full documentation at https://tailwindcss.com.
*/

let colors = {
  black: 'var(--black)',
  transparent: 'var(--transparent)',
  white: 'var(--white)',
  'white-50%': 'var(--white-50)',
  primary: 'var(--primary)',
  'primary-dark': 'var(--primary-dark)',
  'primary-70%': 'var(--primary-70)',
  'primary-50%': 'var(--primary-50)',
  'primary-30%': 'var(--primary-30)',
  'primary-10%': 'var(--primary-10)',
  'sidebar-icon': 'var(--sidebar-icon)',
  'grey-darkest': '#3d4852',
  'grey-darker': '#606f7b',
  'grey-dark': '#8795a1',
  'grey': '#b8c2cc',
  'grey-light': '#dae1e7',
  'grey-lighter': '#f1f5f8',
  'grey-lightest': '#f8fafc',
  'white': '#ffffff',

  'red-darkest': '#3b0d0c',
  'red-darker': '#621b18',
  'red-dark': '#cc1f1a',
  'red': '#e3342f',
  'red-light': '#ef5753',
  'red-lighter': '#f9acaa',
  'red-lightest': '#fcebea',

  'orange-darkest': '#462a16',
  'orange-darker': '#613b1f',
  'orange-dark': '#de751f',
  'orange': '#f6993f',
  'orange-light': '#faad63',
  'orange-lighter': '#fcd9b6',
  'orange-lightest': '#fff5eb',

  'yellow-darkest': '#453411',
  'yellow-darker': '#684f1d',
  'yellow-dark': '#f2d024',
  'yellow': '#ffed4a',
  'yellow-light': '#fff382',
  'yellow-lighter': '#fff9c2',
  'yellow-lightest': '#fcfbeb',

  'green-darkest': '#0f2f21',
  'green-darker': '#1a4731',
  'green-dark': '#1f9d55',
  'green': '#38c172',
  'green-light': '#51d88a',
  'green-lighter': '#a2f5bf',
  'green-lightest': '#e3fcec',

  'teal-darkest': '#0d3331',
  'teal-darker': '#20504f',
  'teal-dark': '#38a89d',
  'teal': '#4dc0b5',
  'teal-light': '#64d5ca',
  'teal-lighter': '#a0f0ed',
  'teal-lightest': '#e8fffe',

  'blue-darkest': '#12283a',
  'blue-darker': '#1c3d5a',
  'blue-dark': '#2779bd',
  'blue': '#3490dc',
  'blue-light': '#6cb2eb',
  'blue-lighter': '#bcdefa',
  'blue-lightest': '#eff8ff',

  'indigo-darkest': '#191e38',
  'indigo-darker': '#2f365f',
  'indigo-dark': '#5661b3',
  'indigo': '#6574cd',
  'indigo-light': '#7886d7',
  'indigo-lighter': '#b2b7ff',
  'indigo-lightest': '#e6e8ff',

  'purple-darkest': '#21183c',
  'purple-darker': '#382b5f',
  'purple-dark': '#794acf',
  'purple': '#9561e2',
  'purple-light': '#a779e9',
  'purple-lighter': '#d6bbfc',
  'purple-lightest': '#f3ebff',

  'pink-darkest': '#451225',
  'pink-darker': '#6f213f',
  'pink-dark': '#eb5286',
  'pink': '#f66d9b',
  'pink-light': '#fa7ea8',
  'pink-lighter': '#ffbbca',
  'pink-lightest': '#ffebef',
  logo: 'var(--logo)',
  info: 'var(--info)',
  danger: 'var(--danger)',
  warning: 'var(--warning)',
  success: 'var(--success)',
  '90-half': 'var(--90-half)',
  90: 'var(--90)',
  80: 'var(--80)',
  70: 'var(--70)',
  60: 'var(--60)',
  50: 'var(--50)',
  40: 'var(--40)',
  30: 'var(--30)',
  20: 'var(--20)',
}

let svgFillColors = global.Object.assign({ current: 'currentColor' }, colors)



module.exports = Object.assign({},require('tailwindcss/defaultConfig')(),{
  colors,textColors: colors,
  backgroundColors: colors,
  borderColors: global.Object.assign(
    {
      default: colors['grey-light'],
    },
    colors
  ),
  backgroundSize: {
    auto: 'auto',
    cover: 'cover',
    contain: 'contain',
  },
  svgFill: svgFillColors,
  svgStroke: {
    current: 'currentColor',
  },
  borderWidths: {
    default: '1px',
    '0': '0',
    '2': '2px',
    '4': '4px',
    '8': '8px',
  },
  borderRadius: {
    none: '0',
    sm: '.125rem',
    default: '.25rem',
    lg: '.5rem',
    full: '9999px',
  },
  fonts: {
    sans: ['Nunito', 'system-ui', 'BlinkMacSystemFont', '-apple-system', 'sans-serif'],
    serif: [
      'Constantia',
      'Lucida Bright',
      'Lucidabright',
      'Lucida Serif',
      'Lucida',
      'DejaVu Serif',
      'Bitstream Vera Serif',
      'Liberation Serif',
      'Georgia',
      'serif',
    ],
    mono: ['Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'],
  },
  textSizes: {
    xs: '.75rem', // 12px
    sm: '.875rem', // 14px
    base: '1rem', // 16px
    lg: '1.125rem', // 18px
    xl: '1.25rem', // 20px
    '2xl': '1.5rem', // 24px
    '3xl': '1.875rem', // 30px
    '4xl': '2.25rem', // 36px
    '5xl': '3rem', // 48px
  },
  fontWeights: {
    hairline: 200,
    thin: 200,
    light: 300,
    normal: 400,
    medium: 400,
    semibold: 600,
    bold: 800,
    extrabold: 800,
    black: 800,
  },

  leading: {
    none: 1,
    tight: 1.25,
    normal: 1.5,
    loose: 2,
    '9': '2.25rem',
    '12': '3rem',
    '36': '2.25rem',
  },

  tracking: {
    tight: '-0.05em',
    normal: '0',
    wide: '0.05em',
  },
  height:{
    '9': '2.25rem',
    '12': '3rem',
    full: '100%',
    'btn-sm': '1.875rem',
  }
})
