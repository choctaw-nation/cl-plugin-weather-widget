import { useState, useEffect } from '@wordpress/element';
import {
	Button,
	Panel,
	ExternalLink,
	Snackbar,
	TextControl,
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

type Props = {
	nonce: string;
};
type Settings = {
	apiKey: string;
};
export default function App( { nonce }: Props ) {
	const { settings, isLoading, isSaving, notice, setSettings, handleSubmit } =
		useSettings( nonce );
	return (
		<Panel>
			<div style={ { padding: '1rem' } }>
				<form
					onSubmit={ handleSubmit }
					style={ {
						marginBlockEnd: '1rem',
					} }
				>
					<TextControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label="API Key"
						disabled={ isLoading || isSaving }
						value={ settings?.apiKey || '' }
						onChange={ ( value ) =>
							setSettings( ( prev ) => ( {
								...prev,
								apiKey: value,
							} ) )
						}
					/>
					<Button
						variant="primary"
						isBusy={ isLoading || isSaving }
						type="submit"
						style={ { marginBlockStart: '1rem' } }
					>
						Save Settings
					</Button>
					{ notice && (
						<div
							style={ {
								marginBlockStart: '2rem',
								position: 'relative',
							} }
						>
							<Snackbar
								politeness={
									notice.status === 'error'
										? 'assertive'
										: 'polite'
								}
							>
								{ notice.message }
							</Snackbar>
						</div>
					) }
				</form>
				<ExternalLink
					href="https://openweathermap.org/api"
					style={ { marginBlockStart: '1rem' } }
				>
					Don&apos;t have an API key? Get one for free from
					OpenWeatherMap.
				</ExternalLink>
			</div>
		</Panel>
	);
}

function useSettings( nonce: string ) {
	const [ settings, setSettings ] = useState< Settings >();
	const [ isLoading, setIsLoading ] = useState( true );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ notice, setNotice ] = useState< {
		message: string;
		status: 'success' | 'error';
	} | null >( null );

	// Configure apiFetch nonce once on mount
	useEffect( () => {
		apiFetch.use( apiFetch.createNonceMiddleware( nonce ) );
	}, [ nonce ] );

	// Load saved settings from REST API on mount
	useEffect( () => {
		apiFetch( { path: '/cno-weather-widget-api/v1/settings' } )
			.then( ( data ) => {
				setSettings( data );
			} )
			.catch( () => {
				setNotice( {
					message:
						'Failed to load settings. Please refresh the page.',
					status: 'error',
				} );
			} )
			.finally( () => {
				setIsLoading( false );
			} );
	}, [ nonce ] );

	async function handleSubmit( e: React.FormEvent ) {
		e.preventDefault();
		setIsSaving( true );
		setNotice( null );

		try {
			await apiFetch( {
				path: '/cno-weather-widget-api/v1/settings',
				method: 'POST',
				data: settings,
			} );
			setNotice( {
				message: 'Settings saved successfully.',
				status: 'success',
			} );
		} catch {
			setNotice( {
				message: 'Failed to save settings. Please try again.',
				status: 'error',
			} );
		} finally {
			setIsSaving( false );
		}
	}

	return {
		settings,
		isLoading,
		isSaving,
		notice,
		setSettings,
		handleSubmit,
		setNotice,
	};
}
