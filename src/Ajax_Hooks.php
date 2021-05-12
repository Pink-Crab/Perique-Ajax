<?php

declare(strict_types=1);

/**
 * Action & Filter handles
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax;

class Ajax_Hooks {

	/**
	 * Action called when callback throws an exception.
	 */
	public const CALLBACK_EXECUTION_EXCEPTION = 'pinkcrab/ajax/callback_execution_exception';

	/**
	 * Filters the ServerRequestInterface's Request before being passed to callback()
	 */
	public const CALLBACK_REQUEST_FILTER = 'pinkcrab/ajax/callback_request_filter';

	/**
	 * Filters the ResponseInterfaces's Response before being passed from callback() to emitter.
	 */
	public const CALLBACK_RESPONSE_FILTER = 'pinkcrab/ajax/callback_response_filter';

	/**
	 * Filters the response from the nonce check used within an ajax callback
	 */
	public const REQUEST_NONCE_VERIFICATION = 'pinkcrab/ajax/request_nonce_verification';
}
