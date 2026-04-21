<?php
// Heading
$_['heading_title']                = 'PayTheFly - Crypto Payments';

// Text
$_['text_extension']               = 'Extensions';
$_['text_success']                 = 'Success: PayTheFly payment settings saved!';
$_['text_edit']                    = 'Edit PayTheFly Configuration';
$_['text_bsc']                     = 'BNB Smart Chain (BSC)';
$_['text_tron']                    = 'TRON';
$_['text_chain_help']              = 'BSC: Chain ID 56, 18 decimals for native BNB. TRON: Chain ID 728126428, 6 decimals for native TRX.';
$_['text_native_bsc']              = 'BSC Native (BNB): 0x0000000000000000000000000000000000000000';
$_['text_native_tron']             = 'TRON Native (TRX): T9yD14Nj9j7xAB4dbGeiX9h8unkKHxuWwb';
$_['text_token_help']              = 'For native tokens, use the zero address (BSC) or T9yD14Nj9j7xAB4dbGeiX9h8unkKHxuWwb (TRON). For ERC-20/TRC-20 tokens, use the contract address.';
$_['text_private_key_help']        = 'Private key used to sign EIP-712 typed data. Keep this secret! Required to generate payment URLs.';
$_['text_deadline_help']           = 'How many seconds from now the payment link remains valid. Default: 3600 (1 hour).';
$_['text_debug_help']              = 'When enabled, webhook payloads and signature details are logged for troubleshooting.';

// Tab
$_['tab_general']                  = 'General';
$_['tab_chain']                    = 'Chain & Token';
$_['tab_order_status']             = 'Order Status';

// Entry
$_['entry_project_id']             = 'Project ID';
$_['entry_project_key']            = 'Project Key (Webhook Secret)';
$_['entry_chain']                  = 'Blockchain Network';
$_['entry_contract_address']       = 'PayTheFlyPro Contract Address';
$_['entry_token_address']          = 'Token Address';
$_['entry_token_decimals']         = 'Token Decimals';
$_['entry_private_key']            = 'Signing Private Key';
$_['entry_deadline_offset']        = 'Payment Deadline (seconds)';
$_['entry_pending_status']         = 'Pending Payment Status';
$_['entry_confirmed_status']       = 'Payment Confirmed Status';
$_['entry_failed_status']          = 'Payment Failed Status';
$_['entry_geo_zone']               = 'Geo Zone';
$_['entry_status']                 = 'Status';
$_['entry_sort_order']             = 'Sort Order';
$_['entry_debug']                  = 'Debug Mode';

// Error
$_['error_permission']             = 'Warning: You do not have permission to modify PayTheFly payment settings!';
$_['error_project_id']             = 'Project ID is required!';
$_['error_project_key']            = 'Project Key (webhook secret) is required!';
$_['error_contract_address']       = 'PayTheFlyPro contract address is required!';
$_['error_private_key']            = 'Signing private key is required!';
