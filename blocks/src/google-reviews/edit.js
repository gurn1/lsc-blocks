import { useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button, SelectControl, Spinner, Notice, ColorPalette } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { placeId, placeName, displayMode, refreshInterval, slidesPerView, cardBackgroundColor, cardTextColor } = attributes;
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  const [isSearching, setIsSearching] = useState(false);
  const [isRefreshing, setIsRefreshing] = useState(false);
  const [error, setError] = useState('');

  const search = async () => {
    if (!query.trim()) return;

    setIsSearching(true);
    setError('');

    try {
      const found = await apiFetch({
        path: '/lsc-blocks/v1/google-reviews/search',
        method: 'POST',
        data: { query },
      });
      setResults(found);
    } catch (err) {
      setError(err.message || __('Search failed.', 'lsc-blocks'));
    } finally {
      setIsSearching(false);
    }
  };

  const selectPlace = (place) => {
    setAttributes({ placeId: place.id, placeName: place.name });
    setResults([]);
    setQuery('');
  };

  const refreshNow = async () => {
    setIsRefreshing(true);
    setError('');

    try {
      await apiFetch({
        path: `/lsc-blocks/v1/google-reviews/refresh/${placeId}`,
        method: 'POST',
      });
    } catch (err) {
      setError(err.message || __('Refresh failed.', 'lsc-blocks'));
    } finally {
      setIsRefreshing(false);
    }
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Business Location', 'lsc-blocks')}>
          {placeName ? (
            <>
              <p><strong>{placeName}</strong></p>
              <Button variant="secondary" onClick={() => setAttributes({ placeId: '', placeName: '' })}>
                {__('Change location', 'lsc-blocks')}
              </Button>
            </>
          ) : (
            <>
              <TextControl
                label={__('Search for your business', 'lsc-blocks')}
                value={query}
                onChange={setQuery}
                onKeyDown={(e) => e.key === 'Enter' && search()}
              />
              <Button variant="primary" onClick={search} disabled={isSearching}>
                {isSearching ? <Spinner /> : __('Search', 'lsc-blocks')}
              </Button>

              {results.map((place) => (
                <Button
                  key={place.id}
                  variant="tertiary"
                  onClick={() => selectPlace(place)}
                  style={{ display: 'block', textAlign: 'left', width: '100%' }}
                >
                  <strong>{place.name}</strong><br />
                  <small>{place.address}</small>
                </Button>
              ))}
            </>
          )}
        </PanelBody>

        <PanelBody title={__('Display', 'lsc-blocks')}>
          <SelectControl
            label={__('Layout', 'lsc-blocks')}
            value={displayMode}
            options={[
              { label: __('List', 'lsc-blocks'), value: 'list' },
              { label: __('Grid', 'lsc-blocks'), value: 'grid' },
              { label: __('Slider', 'lsc-blocks'), value: 'slider' },
            ]}
            onChange={(value) => setAttributes({ displayMode: value })}
          />

          {displayMode === 'slider' && (
            <SelectControl
              label={__('Cards per slide', 'lsc-blocks')}
              value={String(slidesPerView)}
              options={[
                { label: '1', value: '1' },
                { label: '2', value: '2' },
                { label: '3', value: '3' },
              ]}
              onChange={(value) => setAttributes({ slidesPerView: parseInt(value, 10) })}
            />
          )}
        </PanelBody>

        {placeName && (
          <PanelBody title={__('Refresh Schedule', 'lsc-blocks')}>
            <SelectControl
              label={__('Check for new reviews', 'lsc-blocks')}
              value={refreshInterval}
              options={[
                { label: __('Daily', 'lsc-blocks'), value: 'daily' },
                { label: __('Weekly', 'lsc-blocks'), value: 'weekly' },
                { label: __('Monthly (~30 days)', 'lsc-blocks'), value: 'monthly' },
              ]}
              onChange={(value) => setAttributes({ refreshInterval: value })}
            />
            <Button variant="secondary" onClick={refreshNow} disabled={isRefreshing}>
              {isRefreshing ? <Spinner /> : __('Refresh now', 'lsc-blocks')}
            </Button>
          </PanelBody>
        )}

      </InspectorControls>

      <InspectorControls group="styles">
        <PanelBody title="Cards">
          <p>Card Background Colour</p>

          <ColorPalette
            label={__('Background Colour', 'lsc-blocks')}
            value={cardBackgroundColor}
            onChange={(color) =>
              setAttributes({ cardBackgroundColor: color })
            }
            clearable
          />

          <p>Card Text Colour</p>

          <ColorPalette
            value={cardTextColor}
            onChange={(color) =>
              setAttributes({ cardTextColor: color })
            }
            clearable
          />
          
        </PanelBody>
      </InspectorControls>

      {error && <Notice status="error" isDismissible={false}>{error}</Notice>}

      <div {...useBlockProps()}>
        {placeName ? (
          <p>
            {__('Showing reviews for:', 'lsc-blocks')} <strong>{placeName}</strong>
          </p>
        ) : (
          <p>{__('Configure a business location in the block settings.', 'lsc-blocks')}</p>
        )}
      </div>
    </>
  );
}