( function ( wp ) {
  const { registerBlockBindingsSource } = wp.blocks;
  const { __ } = wp.i18n;

  const DAY_LABELS = {
    monday: __('Monday', 'lsc-blocks'),
    tuesday: __('Tuesday', 'lsc-blocks'),
    wednesday: __('Wednesday', 'lsc-blocks'),
    thursday: __('Thursday', 'lsc-blocks'),
    friday: __('Friday', 'lsc-blocks'),
    saturday: __('Saturday', 'lsc-blocks'),
    sunday: __('Sunday', 'lsc-blocks'),
  };

  registerBlockBindingsSource({
    name: 'lsc-blocks/business-info',
    label: __('Business Info', 'lsc-blocks'),
    canUserEditValue: () => false,

    getValues: function ( { bindings } ) {
      const values = {};
      const data = window.LSC_BUSINESS_INFO || {};

      for ( const [ attributeName, source ] of Object.entries( bindings ) ) {
        const key = source.args?.key;

        if ( key === 'phone' ) values[attributeName] = data.phone || '';
        else if ( key === 'email' ) values[attributeName] = data.email || '';
        else if ( key === 'address' ) values[attributeName] = data.address || '';
        else if ( key === 'hours_today' ) values[attributeName] = data.hoursToday || '';
      }

      return values;
    },

    getFieldsList: function () {
      return [
        { label: __('Phone', 'lsc-blocks'), type: 'string', args: { key: 'phone' } },
        { label: __('Email', 'lsc-blocks'), type: 'string', args: { key: 'email' } },
        { label: __('Address', 'lsc-blocks'), type: 'string', args: { key: 'address' } },
        { label: __("Today's Hours", 'lsc-blocks'), type: 'string', args: { key: 'hours_today' } },
        ...Object.entries(DAY_LABELS).map( ( [ day, label ] ) => ( {
          label: label,
          type: 'string',
          args: { key: `hours_${day}` },
        } ) ),
      ];
    },
  } );
} )( window.wp );